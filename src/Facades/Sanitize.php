<?php declare(strict_types=1);

namespace Iaorana\Framework\Facades;

class Sanitize {
    /**
     * A short hand for htmlspecialchars ENT_QUOTES
     */
    public static function h($val = ''): string {
        return htmlspecialchars(strval($val), ENT_QUOTES);
    }

    /**
     * Anti Directory Traversal
     */
    public static function fileName($val = '', bool $strict = true): string {
        if ($strict) {
            return self::h(basename($val));
        }

        return str_replace('..', '', self::h($val));
    }
}
