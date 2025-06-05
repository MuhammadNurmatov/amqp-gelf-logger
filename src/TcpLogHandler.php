<?php

namespace MuhammadN\AmqpGelfLogger;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Level;
use Monolog\LogRecord;
use MuhammadN\AmqpGelfLogger\Contracts\AmqpGelfServiceContract;
use RuntimeException;

class TcpLogHandler extends AbstractProcessingHandler
{
    protected $socket = null;
    public function __construct(Level $level, ?AmqpGelfServiceContract $socket)
    {
        parent::__construct($level);
        $this->socket = $socket;
    }

    protected function write(LogRecord $record): void
    {
        $formatted = $this->getFormatter()->format($record);
        if ($formatted === false) {
            throw new RuntimeException("Failed to JSON-encode the log record.");
        }

        $sent = $this->socket->send($formatted);

        if ($sent === false) {
            $error = socket_strerror(socket_last_error($this->socket));
            throw new RuntimeException("Failed to send UDP packet: {$error}");
        }
    }

}
