<?php

namespace MuhammadN\AmqpGelfLogger;

enum TransportEnum: string
{
    case UDP = 'udp';
    case RABBITMQ = 'rabbitmq';
}
