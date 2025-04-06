<?php

return [
    'host' => env('LOG_RABBITMQ_HOST'),
    'port' => env('LOG_RABBITMQ_PORT'),
    'user' => env('LOG_RABBITMQ_USER'),
    'password' => env('LOG_RABBITMQ_PASSWORD'),
    'exchange' => env('LOG_RABBITMQ_EXCHANGE'),
    'exchange_type' => env('LOG_RABBITMQ_EXCHANGE'),
    'app_name' => env('APP_NAME', 'Laravel'),
    'app_env' => env('APP_ENV'),
    'level' => env('LOG_RABBITMQ__LEVEL', 'error'),
    'path' => storage_path('logs/amqp-gelf-logger'),
    'name' => 'amqp-gelf-logger',
    'day' => 14
];
