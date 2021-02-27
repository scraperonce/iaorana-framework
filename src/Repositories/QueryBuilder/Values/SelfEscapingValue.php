<?php declare(strict_types=1);

namespace Iaorana\Framework\Repositories\QueryBuilder\Values;

use Iaorana\Framework\ValueObjects\ValueObject;

interface SelfEscapingValue extends ValueObject {
    public function escape(): string;
}
