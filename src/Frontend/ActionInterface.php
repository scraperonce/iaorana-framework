<?php declare(strict_types=1);

namespace Iaorana\Framework\Frontend;

use Iaorana\Framework\Facades\Controllers\ControllerInterface;

interface ActionInterface extends ControllerInterface {
    public static function do(): void;
}
