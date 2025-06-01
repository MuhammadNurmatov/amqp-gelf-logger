<?php

namespace MuhammadN\AmqpGelfLogger;

use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class UdpLogger
{
    public function __invoke(array $logConfig)
    {
        $udpLogHandler =  new UdpLogHandler($logConfig['level'] ?? 'debug');
        $udpLogHandler->setFormatter(new AmqpGelfLoggerFormater());

        $fallbackHandler = new RotatingFileHandler(
            $logConfig['path'] ?? storage_path('logs/laravel.log'),
                $logConfig['days'] ?? 14,
            Logger::toMonologLevel($logConfig['level'] ?? 'debug')
        );
        $fallbackHandler->setFormatter(new AmqpGelfLoggerFormater());

        $logger = new Logger($logConfig['name']);
        $logger->pushHandler(
            new AmqpGelfLogHandler($udpLogHandler, $fallbackHandler)
        );

        return $logger;
    }
}
