<?php declare(strict_types=1);

namespace Iaorana\Framework\Repositories;

use NilPortugues\Sql\QueryBuilder\Manipulation\Select;

interface SoftDeletable {
    public function applySoftDeletedWhere(Select $query): void;
}
