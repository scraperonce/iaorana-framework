<?php

namespace Iaorana\Framework\Facades;

use Iaorana\Framework\Exceptions\IOException;
use Iaorana\Framework\Framework;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\RotatingFileHandler;

class Log {
    const DEFAULT_CHANNEL = 'development';

    /**
     * @var Logger[]
     */
    private static $loggers = [];

    /**
     * @var string|null
     */
    public static $logging_dir;

    public static function getLogger($channel = self::DEFAULT_CHANNEL): Logger {

        if (!self::$logging_dir) {
            throw new IOException('Logging dir is not configured.');
        }

        if (!self::$loggers[$channel]) {
            $logLevel = Framework::isDebug() ? Logger::DEBUG : Logger::WARNING;
            $logger = new Logger($channel);
            $logger->pushHandler(new StreamHandler(self::$logging_dir . '/' . strtolower($channel) . '.log', $logLevel));
            $logger->pushHandler(new RotatingFileHandler(self::$logging_dir . '/' . strtolower($channel) . '.log'));

            self::$loggers[$channel] = $logger;
        }

        return self::$loggers[$channel];
    }
}
