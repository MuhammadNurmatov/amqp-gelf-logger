<?php

namespace MuhammadN\AmqpGelfLogger\Contracts;

interface AmqpGelfServiceContract
{
    public function send(mixed $message);

    public function transport(): string;
}
