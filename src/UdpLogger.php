<?php

namespace MuhammadN\AmqpGelfLogger;

use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class UdpLogger
{
    public function __invoke(array $logConfig)
    {
        $level = Logger::toMonologLevel($logConfig['level'] ?? 'debug');

        $udpLogHandler =  new UdpLogHandler($level);
        $udpLogHandler->setFormatter(new AmqpGelfLoggerFormater());

        $fallbackHandler = new RotatingFileHandler(
            $logConfig['path'] ?? storage_path('logs/laravel.log'),
            $logConfig['days'] ?? 14,
            $level
        );
        $fallbackHandler->setFormatter(new AmqpGelfLoggerFormater());

        $logger = new Logger($logConfig['name']);
        $logger->pushHandler(
            new AmqpGelfLogHandler($udpLogHandler, $fallbackHandler)
        );

        return $logger;
    }
}
