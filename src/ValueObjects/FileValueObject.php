<?php declare(strict_types=1);

namespace Iaorana\Framework\ValueObjects;

interface FileValueObject extends ValueObject  {
    public function fileOriginalName(): string;
    public function filePath(): string;
    public function output(): void;

    /** @deprecated */
    public function valueOf(): string;
}
