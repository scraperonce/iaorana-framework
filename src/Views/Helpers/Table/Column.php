<?php declare(strict_types=1);

namespace Iaorana\Framework\Views\Helpers\Table;

use Iaorana\Framework\Facades\Sanitize;

class Column {
    public static function checkbox(string $key, $value, array $attributes = []): string {
        $key = Sanitize::h($key);
        $value = Sanitize::h(strval($value));
        $attrs = self::buildAttributes($attributes, 'check-column');

        return implode("\n", [
            "<th scope=\"row\" id=\"cb\" ${attrs}>",
            "<input name=\"${key}[]\" value=\"${value}\" type=\"checkbox\">",
            "</th>",
        ]);
    }

    protected static function buildAttributes(array $attributes, string $required_class = ''): string {
        $attr = [];

        $attributes['class'] = $required_class . ' ' . ($attributes['class'] ?? '');

        foreach ($attributes as $key => $val) {
            $attr[] = "${key}=\"" . Sanitize::h($val) . "\"";
        }

        return implode("", $attr);
    }
}
