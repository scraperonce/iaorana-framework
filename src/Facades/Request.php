<?php declare(strict_types=1);

namespace Iaorana\Framework\Facades;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Iaorana\Framework\ValueObjects\Facades\Requests\UploadedFile;

class Request {
    /**
     * @param  $key
     * @param  $default
     * @return mixed
     */
    public static function postData($key, $default = '') {
        if (!isset($_POST[$key])) {
            return $default;
        }

        return $_POST[$key];
    }

    /**
     * @param  $key
     * @param  $default
     * @return mixed
     */
    public static function getParam($key, $default = '') {
        if (!isset($_GET[$key])) {
            return $default;
        }

        return $_GET[$key];
    }

    /**
     * @param  $key
     * @param  $default
     * @return mixed
     */
    public static function anyParam($key, $default = '') {
        if (self::postData($key, $default)) {
            return self::postData($key, $default);
        }

        return self::getParam($key, $default);
    }

    /**
     * @throws \InvalidArgumentException
     */
    public static function uploadedFile(string $key): UploadedFile {
        return UploadedFile::fromFileRequest($_FILES[$key]);
    }

    public static function uploadedFileExists(string $key): bool {
        return UploadedFile::exists($_FILES[$key]);
    }

    public static function is(string $method): bool {
        return strtoupper($method) === $_SERVER['REQUEST_METHOD'];
    }

    public static function uri(bool $full = false): string {
        if ($full) {
            return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http")
                . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        } else {
            return $_SERVER['REQUEST_URI'];
        }
    }

    public static function extractFromUri(string $pattern, ?string $uri = null): Collection {
        $uri = $uri ?? self::uri();
        $matches = [];

        if (preg_match($pattern, $uri, $matches)) {
            return new ArrayCollection($matches);
        } else {
            return new ArrayCollection([]);
        }
    }
}
