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
        $this->setHandler();
    }

    public  function setHandler()
    {
        $this->logHandler = match($this->service?->transport()) {
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
