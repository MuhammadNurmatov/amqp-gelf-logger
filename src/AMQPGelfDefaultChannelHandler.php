<?php

namespace MuhammadN\AmqpGelfLogger;

use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Monolog\LogRecord;
use PhpAmqpLib\Exception\AMQPIOException;

class AMQPGelfDefaultChannelHandler
{
    private $fallbackHandler;
    public function __construct()
    {
        $config = config('amqp-gelf-logger');
        $fallbackHandler = new RotatingFileHandler(
            $config['path'] ?? storage_path('logs/amqp-gelf-logger/laravel.log'),
            $config['days'] ?? 14,
            Logger::toMonologLevel($config['level'] ?? 'debug')
        );

        $fallbackHandler->setFormatter(new AmqpGelfLoggerFormater());
        $this->fallbackHandler = $fallbackHandler;
    }

    public function handle(LogRecord $record, ?AMQPIOException $e = null):void
    {
        $record->with(message: $e->getMessage(), context:$e->getTraceAsString());
        $this->fallbackHandler->handle($record);
    }

    public function handleBatch(array $records): void
    {
        $this->fallbackHandler->handleBatch($records);
    }


}
