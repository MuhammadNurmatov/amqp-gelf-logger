<?php

namespace MuhammadN\AmqpGelfLogger\Services;

use MuhammadN\AmqpGelfLogger\Contracts\AmqpGelfServiceContract;
use MuhammadN\AmqpGelfLogger\TransportEnum;

class AmqpGelfService
{
    public array $config;
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function factory(string $transport): AmqpGelfServiceContract
    {
        return match ($transport) {
            TransportEnum::UDP->value => new UdpSocketService($this->config),
            TransportEnum::RABBITMQ->value => new RabbitMQService($this->config),
            default => null
        };
    }
}
