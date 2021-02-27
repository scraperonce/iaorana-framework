<?php

namespace Iaorana\Framework\ValueObjects\Facades\Pagination;

use Doctrine\Common\Collections\ArrayCollection;
use Iaorana\Framework\Presentations\ViewAttributes;

class PaginationAttributes extends ViewAttributes {
    public static function getDefaultValue() {
        return [
            'resources'   => new ArrayCollection(),
            'itemCount'   => 0,
            'paged'       => 1,
            'pageCount'   => 1,
            'firstPage'   => 1,
            'prevPage'    => 1,
            'currentPage' => 1,
            'nextPage'    => 1,
            'lastPage'    => 1,
            'isFirstPage' => true,
            'isLastPage'  => true,
            'itemsPerPage' => 0,
            'pagesToShow' => 10,
            'firstPageInRange' => 1,
            'firstPageInRange' => 1,
            'pagesInRange'     => new ArrayCollection(),
        ];
    }

    public function __construct(array $elements = []) {
        parent::__construct(array_merge(static::getDefaultValue(), $elements));
    }
}
