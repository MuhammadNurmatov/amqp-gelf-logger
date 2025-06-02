<?php

namespace MuhammadN\AmqpGelfLogger\Providers;

use Illuminate\Support\ServiceProvider;
use MuhammadN\AmqpGelfLogger\AmqpGelfLogHandler;
use MuhammadN\AmqpGelfLogger\Services\AmqpGelfService;
use MuhammadN\AmqpGelfLogger\Services\UdpSocketService;

class AmqpGelfLoggerServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->singleton(AmqpGelfLogHandler::class, function ($app) {
            $config = $app['config']->get('amqp-gelf-logger', []);

            $transport = $config['transport'];

            $service =  (new AmqpGelfService($config[$transport]))->factory($transport);

            $handler = new AmqpGelfLogHandler($service);
            $handler->setHandler($transport);

            return $handler;
        });
    }
}
