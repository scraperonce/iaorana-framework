<?php declare(strict_types=1);

namespace Iaorana\Framework\ValueObjects;

interface MimeTypedFileValueObject extends FileValueObject {
    public function fileMimeType(): string;
}
