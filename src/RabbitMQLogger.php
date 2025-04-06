<?php

namespace AmqpGelfLogger;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class RabbitMQLogger
{
    public function __invoke(array $LogConfig): Logger
    {
        $rabbitMqLogHandler = new RabbitMQLogHandler($LogConfig);
        $rabbitMqLogHandler->setFormatter(new AmqpGelfLoggerFormater());

        $fallbackHandler = new RotatingFileHandler(
            $LogConfig['path'] ?? storage_path('logs/laravel.log'),
            14,
            Logger::toMonologLevel($LogConfig['level'] ?? 'debug')
        );

        $fallbackHandler->setFormatter(new AmqpGelfLoggerFormater());


        $logger = new Logger($LogConfig['name']);
        $logger->pushHandler(
            new AmqpGelfLogHandler($rabbitMqLogHandler, $fallbackHandler)
        );

        return $logger;
    }
}
