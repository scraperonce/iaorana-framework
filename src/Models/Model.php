<?php declare(strict_types=1);

namespace Iaorana\Framework\Models;

use Iaorana\Framework\Models\Types\Type;

interface Model {
    public function valueOf();
    public function get(string $key, string $type = Type::STRING);
    public function isNotEmpty(): bool;
    public function isEmpty(): bool;
}
