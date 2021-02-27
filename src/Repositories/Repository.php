<?php declare(strict_types=1);

namespace Iaorana\Framework\Repositories;

use Iaorana\Framework\Repositories\QueryBuilder\Values\DelegatedPlaceholder;
use Iaorana\Framework\Repositories\QueryBuilder\Values\SelfEscapingValue;
use Iaorana\Framework\Exceptions\DatabaseException;
use Iaorana\Framework\Exceptions\TypeAssertionException;
use Iaorana\Framework\Facades\Debuggers\QueryHistory;
use Iaorana\Framework\Facades\Log;
use Iaorana\Framework\Facades\PaginationCalculator;
use Iaorana\Framework\ValueObjects\Facades\Pagination\PaginationAttributes;
use NilPortugues\Sql\QueryBuilder\Builder\GenericBuilder;
use NilPortugues\Sql\QueryBuilder\Builder\MySqlBuilder;
use NilPortugues\Sql\QueryBuilder\Manipulation\AbstractBaseQuery;
use NilPortugues\Sql\QueryBuilder\Manipulation\Insert;
use NilPortugues\Sql\QueryBuilder\Manipulation\Select;
use NilPortugues\Sql\QueryBuilder\Syntax\OrderBy;

abstract class Repository {
    /**
     * @var \wpdb
     */
    public $db;

    /**
     * Specify the table name without wordpress prefix.
     * @var string
     */
    public $table;

    /**
     * @var string
     */
    protected $use_table = null;

    public static function wpdb(): \wpdb {
        global $wpdb;
        return $wpdb;
    }

    abstract public function getDefaultData(): array;

    public function __construct() {
        global $wpdb;
        $this->db = $wpdb;

        if (null !== $this->use_table) {
            $this->table = "{$wpdb->prefix}{$this->use_table}";
        }
    }

    public function getQueryBuilder(): GenericBuilder {
        return new MySqlBuilder();
    }

    public function start(string $type): AbstractBaseQuery {
        $query = $this->getQueryBuilder()->$type();
        $query->setTable($this->table);
        return $query;
    }

    public function select(Select $query): array {
        $builder = $this->getQueryBuilder();

        if ($this instanceof SoftDeletable) {
            $this->applySoftDeletedWhere($query);
        }

        $sql = $builder->write($query);
        $stmt = $this->prepare($sql, $builder->getValues());

        $result = $this->db->get_results($stmt);

        QueryHistory::add($this->db->last_query);

        if (is_wp_error($result) || $this->db->last_error) {
            $e = new DatabaseException($this->db->last_error);
            $e->setLastQuery($this->db->last_query);

            throw $e;
        }

        return $result;
    }

    public function get(Select $query): ?object {
        $query->limit(0, 1);
        $result = $this->select($query);

        if (empty($result)) {
            return null;
        }

        return $result[0];
    }

    public function count(Select $select_query): int {
        /** @var Select */
        $query = unserialize(serialize($select_query)); // deep clone
        $query->count('*', 'count');
        $builder = $this->getQueryBuilder();

        if ($this instanceof SoftDeletable) {
            $this->applySoftDeletedWhere($query);
        }

        $sql = $builder->write($query);
        $stmt = $this->prepare($sql, $builder->getValues());

        $result = $this->db->get_row($stmt);

        QueryHistory::add($this->db->last_query);

        if (is_wp_error($result) || $this->db->last_error) {
            $e = new DatabaseException($this->db->last_error);
            $e->setLastQuery($this->db->last_query);

            throw $e;
        }

        return empty($result->count) ? 0 : intval($result->count, 10);
    }

    public function insert(Insert $query): int {

        if ($this instanceof Readonly) {
            throw new TypeAssertionException('The repository is marked as readonly.');
        }

        $builder = $this->getQueryBuilder();

        $sql = $builder->write($query);
        $stmt = $this->prepare($sql, $builder->getValues());

        Log::getLogger()->debug("sql", [$sql, $builder->getValues()]);

        $result = $this->db->query($stmt);

        QueryHistory::add($this->db->last_query);

        if (is_wp_error($result) || $this->db->last_error) {
            $e = new DatabaseException($this->db->last_error);
            $e->setLastQuery($this->db->last_query);

            throw $e;
        }

        return $this->db->insert_id;
    }

    public function sort(Select $query, string $order_by, string $order_direction, ?string $table_name = null): void {
        $orderBy = !empty($order_by) ? preg_replace("/[^\w]/", "", $order_by) : 'id';
        $orderDirection = (strtoupper($order_direction) === OrderBy::ASC) ? OrderBy::ASC : OrderBy::DESC;

        $query->orderBy($orderBy, $orderDirection, $table_name ? preg_replace("/[^\w]/", "", $table_name) : null);
    }

    public function paginate(
        Select $query,
        int $count,
        int $current_page_index,
        int $items_per_page
    ): PaginationAttributes {
        $paginator = new PaginationCalculator($current_page_index, $items_per_page);
        $data = $paginator->calculate($count);

        $query->limit(
            $paginator->getOffset(),
            $paginator->getLimit(),
        );

        return $data;
    }

    public function prepare(string $raw_sql, array $values): string {
        $sql = $raw_sql;
        $reversed = array_reverse($values);
        $placeholders = [];

        foreach ($reversed as $key => $val) {
            $p = '%s';
            $v = $val;

            if ($val instanceof DelegatedPlaceholder) {
                $p = $val->placeholder();
                $v = $val->valueOf();
            } elseif ($val instanceof SelfEscapingValue) {
                $v = $val->escape();
            } elseif (is_float($val)) {
                $p = '%f';
            } elseif (is_int($val)) {
                $p = '%d';
            } elseif ($val === "NULL") {
                $p = 'NULL';
                $v = null;
            }

            $sql = str_replace($key, $p, $sql);

            if (!is_null($v)) {
                $placeholders[] = $v;
            }
        }

        $sql = $this->db->prepare($sql, array_reverse($placeholders));

        return $sql;
    }
}
