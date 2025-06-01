<?php

namespace MuhammadN\AmqpGelfLogger;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Level;
use Monolog\LogRecord;
use RuntimeException;

class UdpLogHandler extends AbstractProcessingHandler
{

    protected ?array $config;
    protected $socket = null;
    public function __construct(Level $level)
    {
        parent::__construct($level);

        $this->config = config('amqp-gelf-logger.udp');
        $this->socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
    }

    protected function write(LogRecord $record): void
    {
        $formatted = $this->getFormatter()->format($record);
        if ($formatted === false) {
            throw new RuntimeException("Failed to JSON-encode the log record.");
        }

        $sent = socket_sendto(
            $this->socket,
            $formatted,
            strlen($formatted),
            0,
            $this->config['host'],
            $this->config['port']
        );

        if ($sent === false) {
            $error = socket_strerror(socket_last_error($this->socket));
            throw new RuntimeException("Failed to send UDP packet: {$error}");
        }

    }

}
