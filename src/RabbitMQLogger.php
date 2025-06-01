<?php

namespace MuhammadN\AmqpGelfLogger;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class RabbitMQLogger
{
    public function __invoke(array $LogConfig): Logger
    {
        $level =  Logger::toMonologLevel($LogConfig['level'] ?? 'debug');

        $rabbitMqLogHandler = new RabbitMQLogHandler($level);
        $rabbitMqLogHandler->setFormatter(new AmqpGelfLoggerFormater());

        $fallbackHandler = new RotatingFileHandler(
            $LogConfig['path'] ?? storage_path('logs/laravel.log'),
            $LogConfig['days'] ?? 14,
            $level
        );

        $fallbackHandler->setFormatter(new AmqpGelfLoggerFormater());


        $logger = new Logger($LogConfig['name']);
        $logger->pushHandler(
            new AmqpGelfLogHandler($rabbitMqLogHandler, $fallbackHandler)
        );

        return $logger;
    }
}
