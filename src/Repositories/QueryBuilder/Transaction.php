<?php declare(strict_types=1);

namespace Iaorana\Framework\Repositories\QueryBuilder;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Transaction {
    /**
     * @var \wpdb
     */
    public $db;

    /**
     * @var Collection
     */
    public $tables;


    public function __construct(string ...$tables) {
        global $wpdb;
        $this->db = $wpdb;
        $this->tables = new ArrayCollection($tables);
    }

    public function begin() {
        $tables = $this->tables->map(function (string $name) {
            return "${name} WRITE";
        })->toArray();

        $this->db->query('START TRANSACTION');
        $this->db->query("LOCK TABLES " . implode(', ', $tables));
    }

    public function rollback() {
        $this->db->query('ROLLBACK');
        $this->db->query('UNLOCK TABLES');
    }

    public function commit() {
        $this->db->query('COMMIT');
        $this->db->query('UNLOCK TABLES');
    }
}
