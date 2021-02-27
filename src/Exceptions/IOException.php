<?php declare(strict_types=1);

namespace Iaorana\Framework\Exceptions;

class IOException extends \Exception implements Throwable {
    public static function notExists(string $path) {
        return new self("File is not exists:  " . $path);
    }
    public static function isEmpty(string $path) {
        return new self("File is empty: " . $path);
    }
}
