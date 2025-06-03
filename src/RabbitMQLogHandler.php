<?php

namespace MuhammadN\AmqpGelfLogger;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Level;
use Monolog\LogRecord;
use MuhammadN\AmqpGelfLogger\Contracts\AmqpGelfServiceContract;
use RuntimeException;

class RabbitMQLogHandler extends AbstractProcessingHandler
{

    public ?array $config;
    public ?AmqpGelfServiceContract $rabbit;
    public function __construct(Level $level, ?AmqpGelfServiceContract $rabbit) {

        parent::__construct($level);
        $this->rabbit = $rabbit;
    }

    protected function write(LogRecord $record): void
    {
        $formatted = $this->getFormatter()->format($record);
        if ($formatted === false) {
            throw new RuntimeException("Failed to JSON-encode the log record.");
        }

        $this->rabbit->send($formatted);
    }
}
