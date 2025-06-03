<?php

namespace MuhammadN\AmqpGelfLogger\Contracts;

interface AmqpGelfServiceContract
{
    public function send(mixed $message);
}
