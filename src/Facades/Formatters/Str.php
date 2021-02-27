<?php declare(strict_types=1);

namespace Iaorana\Framework\Facades\Formatters;

class Str {
    public static function dateFormat($datetime, string $format = 'Y-m-d H:i:s') {
        $timestamp = strtotime($datetime);

        return date_i18n($format, $timestamp);
    }
}
