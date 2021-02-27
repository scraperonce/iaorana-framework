<?php declare(strict_types=1);

namespace Iaorana\Framework\Validators;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

abstract class Validator implements Validatable {

    /**
     * @var Collection
     */
    protected $errors = [];

    public function __construct() {
        $this->errors = new ArrayCollection([]);
    }

    abstract public function validate(): bool;

    public function setErrors(array $errors): void {
        $this->errors = new ArrayCollection($errors);
    }

    public function addError(string $message): void {
        $this->errors->add($message);
    }

    /**
     * @return Collection<string>
     */
    public function getErrors(): Collection {
        return $this->errors;
    }
}
