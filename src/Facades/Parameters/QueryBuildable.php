<?php declare(strict_types=1);

namespace Iaorana\Framework\Facades\Parameters;

use NilPortugues\Sql\QueryBuilder\Manipulation\QueryInterface;

interface QueryBuildable {
    public function query(QueryInterface &$query): void;
}
