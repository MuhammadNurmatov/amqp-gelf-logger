<?php

namespace MuhammadN\AmqpGelfLogger;

use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class AmqpGelfLogger
{
    public function __invoke(array $logConfig)
    {
        $level = Logger::toMonologLevel($logConfig['level'] ?? 'debug');

        $amqpLogHandler = app(AmqpGelfLogHandler::class);
        $amqpLogHandler?->setLevel($level);
        $amqpLogHandler?->setFormatter(new AmqpGelfLoggerFormater());

        $fallbackHandler = new RotatingFileHandler(
            $logConfig['path'] ?? storage_path('logs/laravel.log'),
            $logConfig['days'] ?? 14,
            $level
        );
        $fallbackHandler->setFormatter(new AmqpGelfLoggerFormater());

        $logger = new Logger($logConfig['name']);
        $logger->pushHandler(
            new UnifiedLogHandler($amqpLogHandler, $fallbackHandler)
        );

        return $logger;
    }

}
