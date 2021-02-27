<?php declare(strict_types=1);

namespace Iaorana\Framework\Models;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Iaorana\Framework\Models\Types\Type;

abstract class Record implements Model, Convertible {
    /**
     * @var object|null
     */
    public $data = [];

    /**
     * @var int|null
     */
    public $ID = null;

    /**
     * @var bool
     */
    public $preloaded = false;

    /**
     * @param object[] $records
     * @return Collection<Record>
     */
    public static function fromArray(array $records): Collection {
        return (new ArrayCollection($records))->map(function (object $record) {
            return new static($record);
        });
    }

    /**
     * @return static
     */
    public static function from(object $record) {
        return new static($record);
    }

    public function __construct(?object $record) {
        $this->ID = $record && ($record->ID ?? $record->id) ? ($record->ID ?? $record->id) : null;
        $this->data = $record;
    }

    public function isNotEmpty(): bool {
        return !$this->isEmpty();
    }

    public function isEmpty(): bool {
        return !$this->data || !$this->ID && $this->ID !== 0;
    }

    public function valueOf(): ?object {
        return $this->data;
    }

    public function get(string $key, string $type = Type::AS_IS) {
        if (!$this->data) {
            return null;
        }

        if (!isset($this->data->$key)) {
            return null;
        }

        $value = Type::typecast($this->data->$key, $type);

        return $value;
    }

    public function __get(string $key) {
        if (property_exists($this, $key)) {
            return $this->$key;
        }

        return $this->get($key);
    }

    public function set(string $key, $value, string $type = Type::AS_IS): void {
        if (!$this->data) {
            $this->data = ((object) []);
        }

        $this->data->$key = Type::typecast($value, $type);
    }

    public function __set(string $key, $value) {
        if (property_exists($this, $key)) {
            return $this->$key = $value;
        }

        return $this->set($key, $value);
    }
}
