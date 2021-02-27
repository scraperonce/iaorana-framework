<?php declare(strict_types=1);

namespace Iaorana\Framework\Validators;

use Doctrine\Common\Collections\Collection;

interface Validatable {
    public function validate(): bool;

    /**
     * @return Collection<string>
     */
    public function getErrors(): Collection;

    public function addError(string $message): void;
}
