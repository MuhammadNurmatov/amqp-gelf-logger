<?php

namespace MuhammadN\AmqpGelfLogger\Providers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use MuhammadN\AmqpGelfLogger\Contracts\AmqpGelfServiceContract;
use MuhammadN\AmqpGelfLogger\Services\AmqpGelfService;

class AmqpGelfLoggerServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->singleton(AmqpGelfServiceContract::class, function () {
            $config = config('amqp-gelf-logger');
            $transport = config('amqp-gelf-logger.transport');
            try {
                $service = new AmqpGelfService($config[$transport]);
                return $service->factory($transport);
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
