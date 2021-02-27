<?php

namespace Iaorana\Framework\Validators;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Iaorana\Framework\Validators\ValidatableCollection;

class ValidatorCollection extends ArrayCollection implements ValidatableCollection {
    /**
     * @param Validatable[] $parameters
     */
    public function __construct(array $parameters) {
        parent::__construct($parameters);
    }

    public function validate(): bool {
        foreach ($this as $parameter) {
            if ($parameter->validate() === false) {
                return false;
            }
        }

        return true;
    }

    public function getErrors(): Collection {
        $errors = $this->map(function (Validatable $parameter) {
            return $parameter->getErrors()->toArray();
        })->toArray();

        return new ArrayCollection(self::flatten([$errors]));
    }

    private static function flatten(array $array) {
        return iterator_to_array(new \RecursiveIteratorIterator(new \RecursiveArrayIterator($array)), false);
    }
}
