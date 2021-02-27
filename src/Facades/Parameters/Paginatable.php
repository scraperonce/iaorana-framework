<?php declare(strict_types=1);

namespace Iaorana\Framework\Facades\Parameters;

interface Paginatable {
    public function currentPageIndex(): int;
    public function itemsPerPage(): int;
}
