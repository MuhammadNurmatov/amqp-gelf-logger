<?php

namespace MuhammadN\AmqpGelfLogger;

use Monolog\Logger;
use Monolog\LogRecord;

class AmqpGelfLogger
{

    public function __invoke(LogRecord $record)
    {
        $level = Logger::toMonologLevel($logConfig['level'] ?? 'debug');

        $amqpLogHandler = app(AmqpGelfLogHandler::class);
        $amqpLogHandler->setLevel($level);

    }

}
