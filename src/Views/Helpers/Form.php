<?php declare(strict_types=1);

namespace Iaorana\Framework\Views\Helpers;

use Iaorana\Framework\Facades\Controllers\ControllerInterface;
use Iaorana\Framework\Facades\Parameters\Noncable;
use Iaorana\Framework\Facades\Parameters\RequestAccessor;
use Iaorana\Framework\Facades\Parameters\Sortable;
use Iaorana\Framework\Facades\Parameters\ValidatableParameterInterface;
use Iaorana\Framework\Facades\Post;
use Iaorana\Framework\Facades\Sanitize;

class Form {

    public static function preventCacheField(): string {
        return '<input type="hidden" name="_" value="' . uniqid() . '" />';
    }

    public static function start(ControllerInterface $page, string $name, array $attributes = []): string {
        $additional_fields = [];

        if (!$attributes['id']) {
            $attributes['id'] = "${name}Form";
        }

        if (!$attributes['method']) {
            $attributes['method'] = strtolower($page->parameter($name)::method());
        }

        if (!$attributes['enctype']) {
            $attributes['enctype'] = $attributes['method'] === 'post'
                ? "multipart/form-data"
                : "application/x-www-form-urlencoded";
        }

        if (!$attributes['action']) {
            $attributes['action'] = Admin::isOpened()
                ? Admin::url($page::slug())
                : get_permalink(Post::fromSlug($page::slug()));

            if ($attributes['hash']) {
                $attributes['action'] .= "#" . strval($attributes['hash']);
                $attributes['cache'] = false;
            }
        }

        if ($attributes['cache'] === false) {
            $additional_fields[] = self::preventCacheField();
        }

        foreach ($attributes as $key => $val) {
            $attr[] = Sanitize::h($key) . "=\"" . Sanitize::h($val) . "\"";
        }

        return implode("\n", [
            "<form " . implode(" ", $attr) . ">",
            self::formEssentials($page::slug(), $page->parameter($name)),
            implode("\n", $additional_fields),
        ]);
    }

    public static function end():string {
        return "</form>";
    }

    public static function queryUrl(ControllerInterface $page, string $name, array $additional_params = []): string {
        $parameter = $page->parameter($name);

        $sort_request = [];

        if ($parameter instanceof Sortable) {
            $sort_request = [
                'orderby' => $parameter->orderBy(),
                'order' => $parameter->orderDirection()
            ];
        }

        $query = http_build_query(array_merge(
            [$page->parameter($name)::action() => "1"],
            $sort_request,
            $additional_params
        ));

        return wp_nonce_url(
            Admin::url($page::slug()) . "&" . $query,
            $parameter::nonceKey(),
            $parameter::nonceFieldName()
        );
    }

    private static function formEssentials(string $slug, ValidatableParameterInterface $parameter): string {
        return implode("\n", [
            self::buildNonceFields($parameter),
            self::buildActionFields($slug, $parameter),
        ]);
    }

    private static function buildNonceFields(Noncable $parameter): string {
        return $parameter::nonceKey()
            ? wp_nonce_field($parameter::nonceKey(), $parameter::nonceFieldName(), true, false)
            : '';
    }

    private static function buildActionFields(string $slug, RequestAccessor $parameter): string {
        $hiddens = [
            '<input type="hidden" id="' . $parameter::action() . '" name="' . $parameter::action() . '" value="1" />',
        ];

        if (Admin::isOpened()) {
            $hiddens[] = '<input type="hidden" name="page" value="' . $slug . '" />';
        }

        return implode("\n", $hiddens);
    }

}
