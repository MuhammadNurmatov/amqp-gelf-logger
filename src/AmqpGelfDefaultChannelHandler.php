<?php

namespace MuhammadN\AmqpGelfLogger;

use DateTimeImmutable;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Level;
use Monolog\Logger;
use Monolog\LogRecord;
use PhpAmqpLib\Exception\AMQPIOException;

class AmqpGelfDefaultChannelHandler
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

    public function handle(mixed $message):void
    {
       $record = $this->createRecordFromException($message);
        $this->fallbackHandler->handle($record);
    }

    public function handleBatch(array $records): void
    {
        $this->fallbackHandler->handleBatch($records);
    }

    private function createRecordFromException(mixed $message): LogRecord
    {
        return new LogRecord(
            datetime: new DateTimeImmutable(),
            channel: 'amqp-gelf-logger',
            level: Level::Error,
            message: $message,
        );
    }


}
