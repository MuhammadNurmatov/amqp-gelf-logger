<?php

namespace MuhammadN\AmqpGelfLogger;

use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use MuhammadN\AmqpGelfLogger\Contracts\AmqpGelfServiceContract;

class AmqpGelfLogger
{
    public function __invoke(array $logConfig)
    {
        $level = Logger::toMonologLevel($logConfig['level'] ?? 'debug');

        $service = app(AmqpGelfServiceContract::class);
        $amqpLogHandler = new AmqpGelfLogHandler($level, $service);
        $amqpLogHandler->setHandler(config('amqp-gelf-logger.transport'));
        $amqpLogHandler->logHandler?->setFormatter(new AmqpGelfLoggerFormater());

        $fallbackHandler = new RotatingFileHandler(
            $logConfig['path'] ?? storage_path('logs/laravel.log'),
            $logConfig['days'] ?? 14,
            $level
        );
        $fallbackHandler->setFormatter(new AmqpGelfLoggerFormater());

        $logger = new Logger($logConfig['name']);
        $logger->pushHandler(
            new UnifiedLogHandler($amqpLogHandler?->logHandler, $fallbackHandler)
        );

        return $logger;
    }

}
