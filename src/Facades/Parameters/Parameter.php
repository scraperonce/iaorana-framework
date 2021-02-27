<?php declare(strict_types=1);

namespace Iaorana\Framework\Facades\Parameters;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Iaorana\Framework\Facades\Request;
use Iaorana\Framework\Facades\Satisfied\ResultInterface;
use Iaorana\Framework\Models\Types\Type;
use Iaorana\Framework\Validators\Rules\Satisfiable;
use Iaorana\Framework\Validators\Validatable;
use Iaorana\Framework\ValueObjects\Facades\Requests\UploadedFile;

abstract class Parameter implements ValidatableParameterInterface {

    const FORM_METHOD = 'post';
    const FORM_KEY_ACTION = null;
    const FORM_NONCE_KEY = null;
    const REQUEST_NONCE_FIELD_NAME = '_wpnonce';

    protected $errors = [];

    public static function action(): ?string {
        return static::FORM_KEY_ACTION;
    }

    public static function method(): string {
        return static::FORM_METHOD;
    }

    public static function nonceKey(): ?string {
        return static::FORM_NONCE_KEY;
    }

    public static function nonceFieldName(): string {
        return static::REQUEST_NONCE_FIELD_NAME;
    }

    public function getAction(): ?string {
        return $this->input(static::action());
    }

    public function hasAction(): bool {
        return !empty($this->getAction());
    }

    public function inAction(): bool {
        if (strtolower(static::method()) === 'post' && Request::is('post')) {
            return $this->hasAction();
        }

        if (strtolower(static::method()) === 'get' && Request::is('get')) {
            return $this->hasAction();
        }

        return false;
    }

    public function currentValue($key) {
        return !$this->hasError() ? $this->input($key) : '';
    }

    public function input($key, string $type = Type::AS_IS) {
        $result = Request::is('post') ? Request::postData($key) : Request::getParam($key);

        return Type::typecast($result, $type);
    }

    public function all(): Collection {
        return new ArrayCollection($_REQUEST);
    }

    public function file($key): ?UploadedFile {
        if (Request::uploadedFileExists($key)) {
            try {
                return Request::uploadedFile($key);
            } catch (\Throwable $e) {
                return null;
            }
        }

        return null;
    }

    public function addError(string $message): void {
        $this->errors[] = $message;
    }

    public function getErrors(): Collection {
        return new ArrayCollection($this->errors);
    }

    public function hasError(): bool {
        return !empty($this->errors);
    }

    public function invoke(string $klass) {
        return (new $klass($this))->execute();
    }

    protected function validateWith(Validatable $validator) {
        if ($validator->validate() === false) {
            $this->errors[] = array_merge($this->errors, $validator->getErrors()->toArray());
        }
    }

    protected function validateRule(Satisfiable $rule) {
        if ($rule->satisfied() === false) {
            $this->errors[] = $rule->unsatisfiedReason();
        }
    }

    protected function validateSatisfied(ResultInterface $result) {
        if ($result->passed() === false) {
            $this->errors[] = $result->getReasons();
        }
    }

    abstract public function validate(): bool;
}
