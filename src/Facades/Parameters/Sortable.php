<?php declare(strict_types=1);

namespace Iaorana\Framework\Facades\Parameters;

interface Sortable {
    public function orderBy(): ?string;
    public function orderDirection(): ?string;
}
