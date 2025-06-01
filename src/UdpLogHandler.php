<?php

namespace MuhammadN\AmqpGelfLogger;

use Illuminate\Support\Facades\Log;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Level;
use Monolog\LogRecord;
use MuhammadN\AmqpGelfLogger\Services\UdpSocketService;
use RuntimeException;

class UdpLogHandler extends AbstractProcessingHandler
{
    protected $socket = null;
    public function __construct(Level $level, UdpSocketService $socket)
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
