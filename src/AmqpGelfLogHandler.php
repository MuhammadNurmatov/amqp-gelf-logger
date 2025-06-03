<?php

namespace MuhammadN\AmqpGelfLogger;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Level;
use MuhammadN\AmqpGelfLogger\Contracts\AmqpGelfServiceContract;

class AmqpGelfLogHandler
{
    public Level $level;
    public ?AmqpGelfServiceContract $service = null;

    public ?AbstractProcessingHandler $logHandler = null;
    public function __construct(Level $level, ?AmqpGelfServiceContract $service)
    {
        $this->level = $level;
        $this->service = $service;
    }

    public  function setHandler(string $transport)
    {
        $this->logHandler = match($transport) {
            TransportEnum::UDP->value => new UdpLogHandler($this->level, $this->service),
            TransportEnum::RABBITMQ->value => new RabbitMQLogHandler($this->level, $this->service),
            default => null
        };
    }

    public function setLevel(Level $level): void
    {
        $this->level = $level;
    }


}
