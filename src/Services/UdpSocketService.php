<?php

namespace MuhammadN\AmqpGelfLogger\Services;

use MuhammadN\AmqpGelfLogger\Contracts\AmqpGelfServiceContract;
use MuhammadN\AmqpGelfLogger\TransportEnum;
use RuntimeException;

class UdpSocketService implements AmqpGelfServiceContract
{
    private $socket = null;
    public array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
        socket_set_option($this->socket, SOL_SOCKET, SO_SNDBUF, $this->config['max_buffer'] ?? 1400);
    }


    public function send(mixed $message): bool|int
    {
       return  socket_sendto(
            $this->socket,
             $message,
            strlen($message),
            0,
            $this->config['host'],
            $this->config['port']
        );
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
