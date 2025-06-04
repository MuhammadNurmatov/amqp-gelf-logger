<?php

namespace MuhammadN\AmqpGelfLogger\Services;

use MuhammadN\AmqpGelfLogger\Contracts\AmqpGelfServiceContract;
use MuhammadN\AmqpGelfLogger\TransportEnum;
use RuntimeException;

class UdpSocketService implements AmqpGelfServiceContract
{
    private $socket = null;
    public array $config;

    public bool $bound = false;
    public function __construct(array $config)
    {
        $this->config = $config;
        $this->socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
    }

    private function bind(): void
    {
        $localPort = $this->config['local_port'] ?? 0;
        $localHost = $this->config['local_host'] ?? '0.0.0.0';
        socket_set_option($this->socket, SOL_SOCKET, SO_SNDBUF, $this->config['max_buffer'] ?? 1400);
        if (!socket_bind($this->socket, $localHost, $localPort)) {
            $error = socket_strerror(socket_last_error($this->socket));
            throw new RuntimeException("Failed to bind UDP socket on port {$localPort}: {$error}");
        }

        $this->bound = true;
    }


    public function send(mixed $message): bool|int
    {
        if (!$this->bound) {
            $this->bind();
        }

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
