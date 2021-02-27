<?php declare(strict_types=1);

namespace Iaorana\Framework\ValueObjects;

interface StringValueObject extends ValueObject {
    /**
     * @return static
     */
    public static function of(string $value);
    public function is(string $value): bool;
    public function valueOf(): string;
}
