<?php

namespace MuhammadN\AmqpGelfLogger\Services;

use MuhammadN\AmqpGelfLogger\Contracts\AmqpGelfServiceContract;
use MuhammadN\AmqpGelfLogger\TransportEnum;
use RuntimeException;

class TcpSocketService implements AmqpGelfServiceContract
{
    private $socket = null;
    public array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if (!socket_connect($this->socket, $this->config['host'], $this->config['port'])) {
            throw new RuntimeException("Failed to connection ". socket_strerror(socket_last_error($this->socket)));
        }
    }


    public function send(mixed $message): bool|int
    {
       return socket_write($this->socket, $message, strlen($message));
    }

    public function transport(): string
    {
        return TransportEnum::UDP->value;
    }

    public function __destruct()
    {
        if ($this->socket !== null) {
            socket_close($this->socket);
        }
    }

}
