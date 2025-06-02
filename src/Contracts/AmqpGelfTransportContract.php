<?php

namespace MuhammadN\AmqpGelfLogger\Contracts;

interface AmqpGelfTransportContract
{
    public function send(mixed $message);
}
