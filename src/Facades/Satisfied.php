<?php declare(strict_types=1);

namespace Iaorana\Framework\Facades;

use Iaorana\Framework\Facades\Satisfied\Result;
use Iaorana\Framework\Validators\Rules\Satisfiable;

class Satisfied {
    /**
     * Tests every subjects is satisfied
     */
    public static function every(Satisfiable ...$satisfiables): Result {
        foreach ($satisfiables as $satisfiable) {
            if ($satisfiable->satisfied() === false) {
                return new Result(false, [ $satisfiable->unsatisfiedReason() ]);
            }
        }

        return new Result(true);
    }

    /**
     * Tests one of subjects is satisfied
     */
    public static function some(Satisfiable ...$satisfiables): Result {
        $reasons = [];

        foreach ($satisfiables as $satisfiable) {
            if ($satisfiable->satisfied() === true) {
                return new Result(true);
            } else {
                $reasons[] = $satisfiable->unsatisfiedReason();
            }
        }

        return new Result(false, $reasons);
    }
}
