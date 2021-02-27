<?php declare(strict_types=1);

namespace Iaorana\Framework\Facades\Satisfied;

class Result implements ResultInterface {
    /**
     * @var false
     */
    private $result = false;

    /**
     * @var array
     */
    private $reasons = [];

    public function __construct(bool $result, array $reasons = []) {
        $this->result = $result;
        $this->reasons = $reasons;
    }

    public function passed(): bool {
        return $this->result === true;
    }

    public function failed(): bool {
        return $this->result === false;
    }

    public function getReasons(): array {
        return $this->reasons;
    }
}
