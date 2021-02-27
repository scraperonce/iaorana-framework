<?php declare(strict_types=1);

namespace Iaorana\Framework\Facades\Satisfied;

interface ResultInterface {
    public function passed(): bool;
    public function failed(): bool;
    public function getReasons(): array;
}
