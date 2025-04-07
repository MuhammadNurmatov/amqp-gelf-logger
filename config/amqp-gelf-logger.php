<?php

return [
    'rabbitmq' => [
        'host' => env('LOG_RABBITMQ_HOST'),
        'port' => env('LOG_RABBITMQ_PORT'),
        'user' => env('LOG_RABBITMQ_USER'),
        'password' => env('LOG_RABBITMQ_PASSWORD'),
        'vhost' => env('LOG_RABBITMQ_VHOST', '/'),
        'exchange' => env('LOG_RABBITMQ_EXCHANGE'),
        'exchange_type' => env('RABBITMQ_EXCHANGE', 'topic'),
        'routing_key' => env('LOG_RABBITMQ_EXCHANGE'),
        'use_tls' => env('LOG_RABBITMQ_USE_TLS', false),
        'verify_peer' => env('LOG_RABBITMQ_VERIFY_PEER', false),
        'verify_peer_name' => env('LOG_RABBITMQ_VERIFY_PEER_NAME', false),
        'cafile' => env('LOG_RABBITMQ_CAFILE', ''),
        'local_cert' => env('LOG_RABBITMQ_LOCAL_CERT', ''),
        'local_pk' => env('LOG_RABBITMQ_LOCAL_PK', ''),
        'app_name' => env('APP_NAME', 'Laravel'),
        'app_env' => env('APP_ENV', 'production'),
        'level' => env('RABBITMQ_LOG_LEVEL', 'debug'),
        'path' => storage_path('logs/logger/logs.log'),
        'days' => 14,
    ]
];
