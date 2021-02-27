<?php declare(strict_types=1);

namespace Iaorana\Framework\Models;

abstract class VirtualRecord implements Model {
    /**
     * @var int|null
     */
    public $ID = null;

    public function __construct(?int $id = null) {
        $this->ID = $id;
    }

    public function isNotEmpty(): bool {
        return !$this->isEmpty();
    }

    abstract public function isEmpty(): bool;
}
