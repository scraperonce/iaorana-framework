<?php declare(strict_types=1);

namespace Iaorana\Framework\Facades\Formatters;

class Num {
    public static function convertBytesToHumanReadable($bytes, int $decimals = 2): string {
        $byte_str = strval($bytes);
        $size = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        $factor = floor((strlen($byte_str) - 1) / 3);

        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . ' ' . @$size[$factor];
    }

    public static function zeroPad($num, $digit = 2): string {
        return str_pad(strval($num), $digit, "0", STR_PAD_LEFT);
    }
}
