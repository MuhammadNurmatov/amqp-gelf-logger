<?php

namespace MuhammadN\AmqpGelfLogger\Providers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use MuhammadN\AmqpGelfLogger\AmqpGelfLogHandler;
use MuhammadN\AmqpGelfLogger\Services\AmqpGelfService;

class AmqpGelfLoggerServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->singleton(AmqpGelfLogHandler::class, function () {
            $config = config('amqp-gelf-logger');
            try {
                $transport = $config['transport'];
                $service =  (new AmqpGelfService($config[$transport]))->factory($transport);

                $handler = new AmqpGelfLogHandler($service);
                $handler->setHandler($transport);

                return $handler;
            } catch (\Exception $e)
            {
                Log::build([
                    'driver' => 'single',
                    'path' => $config[$transport]['path'] ?? storage_path('logs/amqp-gelf-logger/laravel.log')
                ])->emergency($e->getMessage());
            }

            return null;
        });
    }
}
