<?php declare(strict_types=1);

namespace Iaorana\Framework\Facades\Parameters;

interface Noncable {
    public static function nonceKey(): ?string;
    public static function nonceFieldName(): string;
}
