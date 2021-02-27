<?php declare(strict_types=1);

namespace Iaorana\Framework\Components;

use Iaorana\Framework\Facades\Controllers\ControllerInterface;

interface Controller extends ControllerInterface {
    public static function register(): void;
    public static function mountPoint(): array;
    public function view(string $path): void;
}
