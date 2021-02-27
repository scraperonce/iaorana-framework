<?php declare(strict_types=1);

namespace Iaorana\Framework\Validators;

use Doctrine\Common\Collections\Collection;

interface ValidatableCollection extends Collection {
    public function validate(): bool;

    /**
     * @return Collection<string>
     */
    public function getErrors(): Collection;
}
