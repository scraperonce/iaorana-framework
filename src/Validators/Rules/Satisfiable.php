<?php declare(strict_types=1);

namespace Iaorana\Framework\Validators\Rules;

interface Satisfiable {
    public function satisfied(): bool;
    public function unsatisfiedReason(): string;
}
