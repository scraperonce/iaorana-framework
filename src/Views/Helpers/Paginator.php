<?php declare(strict_types=1);

namespace Iaorana\Framework\Views\Helpers;

use Iaorana\Framework\Facades\Sanitize;
use Iaorana\Framework\Presentations\Renderable;
use Iaorana\Framework\ValueObjects\Facades\Pagination\PaginationAttributes;

class Paginator implements Renderable {
    /**
     * @var PaginationAttributes
     */
    protected $pagination;

    /**
     * @var string
     */
    protected $form_name;

    /**
     * @param array $pagination
     */
    public function __construct(PaginationAttributes $pagination, string $form_name = 'index') {
        $this->form_name = $form_name;
        $this->pagination = $pagination;
    }

    public function render(): string {
        ob_start();
        ?>
        <div class="tablenav-pages iaorana-pagination" data-linked-form="<?= Sanitize::h($this->form_name) ?>Form">
            <span class="displaying-num"><?php print $this->pagination->get('itemCount') ?>個の項目</span>

            <?php if ($this->pagination->get('isFirstPage')) : ?>
                <span class="pagination-links">
                    <span class="tablenav-pages-navspan" aria-hidden="true">«</span>
                    <span class="tablenav-pages-navspan" aria-hidden="true">‹</span>
                </span>
            <?php else : ?>
                <a class="first-page" href="#" data-href-id="<?= $this->pagination->get('firstPage') ?>">
                    <span class="screen-reader-text">最初のページ</span>
                    <span class="tablenav-pages-navspan" aria-hidden="true">«</span>
                </a>
                <a class="prev-page" href="#" data-href-id="<?= $this->pagination->get('prevPage') ?>">
                    <span class="screen-reader-text">前ページへ</span>
                    <span class="tablenav-pages-navspan" aria-hidden="true">‹</span>
                </a>
            <?php endif; ?>

            <span class="paging-input">
                <span class="tablenav-paging-text">
                    <span class="current-page tablenav-pages-navspan">
                        <?= $this->pagination->get('currentPage') ?> / <?= $this->pagination->get('pageCount') ?>
                    </span>
                </span>
            </span>

            <?php if ($this->pagination->get('isLastPage')) : ?>
                <span class="pagination-links">
                    <span class="tablenav-pages-navspan" aria-hidden="true">›</span>
                    <span class="tablenav-pages-navspan" aria-hidden="true">»</span>
                </span>
            <?php else : ?>
                <a class="next-page" href="#" data-href-id="<?= $this->pagination->get('nextPage') ?>">
                    <span class="screen-reader-text">次ページへ</span>
                    <span class="tablenav-pages-navspan" aria-hidden="true">›</span>
                </a>
                <a class="last-page" href="#" data-href-id="<?= $this->pagination->get('lastPage') ?>">
                    <span class="screen-reader-text">最後のページ</span>
                    <span class="tablenav-pages-navspan" aria-hidden="true">»</span>
                </a>
            <?php endif; ?>
        </div>
        <?php
        $result = ob_get_clean();

        return $result;
    }
}
