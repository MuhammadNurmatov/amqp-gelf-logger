<?php

namespace MuhammadN\AmqpGelfLogger;

enum TransportEnum: string
{
    case UDP = 'udp';
    case TCP = 'tcp';
    case RABBITMQ = 'rabbitmq';
}
