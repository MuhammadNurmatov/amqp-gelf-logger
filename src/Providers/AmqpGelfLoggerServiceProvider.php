<?php

namespace MuhammadN\AmqpGelfLogger\Providers;

use Illuminate\Support\ServiceProvider;
use MuhammadN\AmqpGelfLogger\Services\UdpSocketService;

class AmqpGelfLoggerServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->singleton(UdpSocketService::class, function ($app) {
            $config = $app['config']->get('amqp-gelf-logger.udp', []);
            return new UdpSocketService($config);
        });
    }
}
