<?php declare(strict_types=1);

namespace Iaorana\Framework\Repositories\QueryBuilder\Values;

use Iaorana\Framework\ValueObjects\ValueObject;

interface DelegatedPlaceholder extends ValueObject {
    public function placeholder(): string;
}
