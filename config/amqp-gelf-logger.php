<?php

return [
    'rabbitmq' => [
        'host' => env('LOG_RABBITMQ_HOST'),
        'port' => env('LOG_RABBITMQ_PORT'),
        'user' => env('LOG_RABBITMQ_USER'),
        'password' => env('LOG_RABBITMQ_PASSWORD'),
        'vhost' => env('RABBITMQ_VHOST', '/'),
        'exchange' => env('RABBITMQ_EXCHANGE', 'test'),
        'exchange_type' => env('RABBITMQ_EXCHANGE', 'topic'),
        'use_tls' => env('RABBITMQ_USE_TLS', false),
        'verify_peer' => env('RABBITMQ_VERIFY_PEER', false),
        'verify_peer_name' => env('RABBITMQ_VERIFY_PEER_NAME', false),
        'cafile' => env('RABBITMQ_CAFILE', ''),
        'local_cert' => env('RABBITMQ_LOCAL_CERT', ''),
        'local_pk' => env('RABBITMQ_LOCAL_PK', ''),
        'app_name' => env('APP_NAME', 'Laravel'),
        'app_env' => env('APP_ENV', 'production'),
        'level' => env('RABBITMQ_LOG_LEVEL', 'debug'),
        'path' => storage_path('logs/logger/logs.log'),
        'days' => 14,
    ]
];
