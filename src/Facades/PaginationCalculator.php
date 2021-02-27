<?php declare(strict_types=1);

namespace Iaorana\Framework\Facades;

use Doctrine\Common\Collections\ArrayCollection;
use Iaorana\Framework\ValueObjects\Facades\Pagination\PaginationAttributes;
use JasonGrimes\Paginator;

class PaginationCalculator {

    /**
     * @var int
     */
    public $paged = 1;

    /**
     * @var int
     */
    public $itemsPerPage = 1;

    public function __construct(int $paged, int $itemsPerPage) {
        $this->setPage($paged);
        $this->setItemsPerPage($itemsPerPage);
    }

    public function setPage(int $paged = 1): void {
        $p = intval($paged, 10);
        $p = $p < 1 ? 1 : $p;
        $this->paged = $p;
    }

    public function setItemsPerPage(int $itemsPerPage = 50): void {
        $p = intval($itemsPerPage, 10);
        $p = $p < 1 ? 1 : $p;
        $this->itemsPerPage = $p;
    }

    public function calculate(int $count, int $pagesToShow = 10): PaginationAttributes {
        $paginator = new Paginator($count, $this->itemsPerPage, $this->paged);
        $paginator->setMaxPagesToShow($pagesToShow);

        $isFirstPage = 1 === $paginator->getCurrentPage();
        $isLastPage = $paginator->getNumPages() === $paginator->getCurrentPage();
        $pages = new ArrayCollection($paginator->getPages());

        return new PaginationAttributes([
            'itemCount'   => $paginator->getTotalItems(),
            'paged'       => $paginator->getNumPages() === 0 ? 0 : $paginator->getCurrentPage(),
            'pageCount'   => $paginator->getNumPages(),
            'firstPage'   => $paginator->getNumPages() === 0 ? 0 : 1,
            'prevPage'    => $paginator->getPrevPage(),
            'currentPage' => $paginator->getNumPages() === 0 ? 0 : $paginator->getCurrentPage(),
            'nextPage'    => $paginator->getNextPage(),
            'lastPage'    => $paginator->getNumPages(),
            'isFirstPage' => $isFirstPage,
            'isLastPage'  => $isLastPage,
            'itemsPerPage' => $paginator->getItemsPerPage(),
            'pagesToShow' => $paginator->getMaxPagesToShow(),
            'firstPageInRange' => ($pages->first())['num'],
            'lastPageInRange'  => ($pages->last())['num'],
            'pagesInRange'     => $pages->map(function (array $page) {
                return $page['num'];
            }),
        ]);
    }

    public function getOffset(): int {
        return  ($this->paged - 1) * $this->itemsPerPage;
    }

    public function getLimit(): int {
        return $this->itemsPerPage;
    }
}
