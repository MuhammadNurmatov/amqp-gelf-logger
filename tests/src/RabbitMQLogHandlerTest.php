<?php

use AmqpGelfLogger\RabbitMQLogger;
use AmqpGelfLogger\RabbitMQLogHandler;
use Monolog\Logger;

use Tests\TestCase;

class RabbitMQLogHandlerTest extends TestCase
{
    public function test_rabbitmq_handle(): void
    {
        $config =  [
        'driver' => 'custom',
        'via' => RabbitMQLogger::class,
        'logger_name' => 'telegram',
        'chat_id' => env('CRM_ERROR_TRACKER'),
        'token' => env('TELEGRAM_BOT_TOKEN'),
        'level' => 'debug',
       ];

       $handler  = new RabbitMQLogHandler($config);

        $this->assertInstanceOf(Logger::class, $handler);
        $this->assertEquals('rabbitmq', $handler->getName());
    }
}
