<?php declare(strict_types=1);

namespace Iaorana\Framework\Facades\Controllers;

use Iaorana\Framework\Facades\Parameters\ValidatableParameterInterface;
use Iaorana\Framework\Validators\Validatable;
use Iaorana\Framework\Validators\ValidatableCollection;

interface ControllerInterface {
    public static function slug(): string;
    public function route(): void;
    public function index(ValidatableParameterInterface $parameter): void;
    public function parameter(string $path): ValidatableParameterInterface;
    public function getValidatorCollection(): ValidatableCollection;
    public function flash(): string;
}
