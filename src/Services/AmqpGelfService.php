<?php

namespace MuhammadN\AmqpGelfLogger\Services;

use MuhammadN\AmqpGelfLogger\Contracts\AmqpGelfTransportContract;
use MuhammadN\AmqpGelfLogger\RabbitMQLogHandler;
use MuhammadN\AmqpGelfLogger\TransportEnum;

class AmqpGelfService
{
    public array $config;
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function factory(string $transport): AmqpGelfTransportContract
    {
        return match ($transport) {
            TransportEnum::UDP->value => new UdpSocketService($this->config),
            TransportEnum::RABBITMQ->value => new RabbitMQService($this->config),
            default => null
        };
    }
}
