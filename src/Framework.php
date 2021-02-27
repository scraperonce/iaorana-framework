<?php

namespace Iaorana\Framework;

use Iaorana\Framework\Facades\Response;

class Framework {
    public static function bootstrap(): void {
        Response::bootstrap();
    }

    public static function isDebug(): bool {
        return $_ENV['MODE'] === 'development';
    }
}
