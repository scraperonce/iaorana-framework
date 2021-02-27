<?php declare(strict_types=1);

namespace Iaorana\Framework\Views\Helpers\Table;

use Iaorana\Framework\Facades\Parameters\Sortable;
use Iaorana\Framework\Facades\Sanitize;

class HeaderColumn extends Column {
    public static function sortable(
        string $key,
        string $label,
        Sortable $sortable,
        string $form_name = 'index',
        array $attributes = []
    ): string {
        $id = Sanitize::h($key);
        $label = Sanitize::h($label);
        $sort_class = $sortable->orderBy() === $key ? 'sorted' : 'sortable';
        $sort_order_class = ($sortable->orderBy() === $key && $sortable->orderDirection() === 'desc') ? 'desc' : 'asc';

        $next_sort = $sort_order_class === 'desc' ? 'asc' : 'desc';

        $attrs = self::buildAttributes($attributes, "manage-column ${sort_class} ${sort_order_class}");

        return implode("\n", [
            "<th scope=\"col\" id=\"column-${id}\" ${attrs}>",
            "<a class=\"iaorana-table-sortable\" href=\"#\" data-linked-form=\""
                . Sanitize::h($form_name)
                . "Form\" data-orderby=\"${id}\" data-order=\"${next_sort}\">",
            "<span>${label}</span>",
            "<span class=\"sorting-indicator\"></span>",
            "</a>",
            "</th>",
        ]);
    }

    public static function default(string $key, string $label, array $attributes = []): string {
        $id = Sanitize::h($key);
        $label = Sanitize::h($label);
        $attrs = self::buildAttributes($attributes, 'manage-column');

        return "<th scope=\"col\" id=\"column-${id}\" ${attrs}>${label}</th>";
    }

    public static function checkAll(array $attributes = []): string {
        $attrs = self::buildAttributes($attributes, 'manage-column column-cb check-column');

        return implode("\n", [
            "<td id=\"cb\" ${attrs}>",
            "<input id=\"cb-select-all-1\" type=\"checkbox\">",
            "</td>",
        ]);
    }
}
