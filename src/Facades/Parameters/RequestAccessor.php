<?php declare(strict_types=1);

namespace Iaorana\Framework\Facades\Parameters;

use Doctrine\Common\Collections\Collection;
use Iaorana\Framework\Models\Types\Type;
use Iaorana\Framework\ValueObjects\Facades\Requests\UploadedFile;

interface RequestAccessor {
    public static function action(): ?string;
    public static function method(): string;
    public function getAction(): ?string;
    public function hasAction(): bool;
    public function inAction(): bool;
    public function input($key, string $type = Type::AS_IS);
    public function file($key): ?UploadedFile;
    public function all(): Collection;
}
