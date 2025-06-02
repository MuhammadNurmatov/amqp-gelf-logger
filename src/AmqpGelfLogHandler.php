<?php

namespace MuhammadN\AmqpGelfLogger;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Level;
use MuhammadN\AmqpGelfLogger\Contracts\AmqpGelfTransportContract;

class AmqpGelfLogHandler
{
    public Level $level;
    public ?AmqpGelfTransportContract $transport = null;

    public ?AbstractProcessingHandler $logHandler = null;
    public function __construct(?AmqpGelfTransportContract $transport)
    {
        $this->transport = $transport;
    }

    public  function setHandler(string $transport)
    {
        $this->logHandler = match($transport) {
            TransportEnum::UDP->value => new UdpLogHandler($this->level, $this->transport),
            TransportEnum::RABBITMQ->value => new RabbitMQLogHandler($this->level, $this->transport),
            default => null
        };
    }

    public function setLevel(Level $level): void
    {
        $this->level = $level;
    }


}
