<?php declare(strict_types=1);

namespace Iaorana\Framework\Exceptions;

class DatabaseException extends \Exception implements Throwable {
    private $last_query = '';

    public function setLastQuery(string $last_query) {
        $this->last_query = $last_query;
    }

    public function getLastQuery(): string {
        return $this->last_query;
    }
}
