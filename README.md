###  Laravel AMQP GELF Logger — Send Logs to Graylog via AMQP or GELF

[![Latest Stable Version](https://poser.pugx.org/muhammadnurmatov/amqp-gelf-logger/v/stable)](https://packagist.org/packages/muhammadnurmatov/amqp-gelf-logger)
[![Total Downloads](https://poser.pugx.org/muhammadnurmatov/amqp-gelf-logger/downloads)](https://packagist.org/packages/muhammadnurmatov/amqp-gelf-logger)
[![License](https://poser.pugx.org/muhammadnurmatov/amqp-gelf-logger/license)](https://packagist.org/packages/muhammadnurmatov/amqp-gelf-logger)

A flexible logger for **Laravel 9+**, sending logs via **AMQP** or **GELF** directly to **Graylog** for real-time centralized logging and monitoring.

---

## 🚀 Installation

Install the package via Composer:

```bash
composer require muhammadnurmatov/amqp-gelf-logger
```
#  Configuration

### 1. Config File

You can manually create the config file at `config/amqp-gelf-logger.php`:

### 2. Edit `config/amqp-gelf-logger.php`

```php
return [
    'rabbitmq' => [
        'host' => env('RABBITMQ_HOST'),
        'port' => env('RABBITMQ_PORT', 5672),
        'user' => env('RABBITMQ_USER'),
        'password' => env('RABBITMQ_PASSWORD'),
        'vhost' => env('RABBITMQ_VHOST', '/'),
        'exchange' => env('RABBITMQ_EXCHANGE'),
        'exchange_type' => env('RABBITMQ_EXCHANGE', 'topic'),
        'routing_key' => env('LOG_RABBITMQ_EXCHANGE'),
        'use_tls' => env('RABBITMQ_USE_TLS', false),
        'verify_peer' => env('RABBITMQ_VERIFY_PEER', false),
        'verify_peer_name' => env('RABBITMQ_VERIFY_PEER_NAME', false),
        'cafile' => env('RABBITMQ_CAFILE'),
        'local_cert' => env('RABBITMQ_LOCAL_CERT'),
        'local_pk' => env('RABBITMQ_LOCAL_PK'),
        'app_name' => env('APP_NAME', 'Laravel'),
        'app_env' => env('APP_ENV', 'production'),
        'level' => env('RABBITMQ_LOG_LEVEL', 'debug'),
        'path' => storage_path('logs/logger/logs.log'),
        'days' => 14,
    ]
];
```
If you don't want to use SSL, set **use_tls = false** in the config.

##  Logging Channel Setup

Add a custom logging channel in `config/logging.php`:
```php
'amqp' => [
    'driver' => 'custom',
    'via' => \MuhammadN\AmqpGelfLogger\RabbitMQLogger::class,
    'name' => 'graylog',
    'level' => 'debug',
    'path' => storage_path('logs/graylog.log'),
    'days' => 14,
],
```
##  Usage

You can use the custom logger like any other Laravel log channel:

```php
use Illuminate\Support\Facades\Log;

Log::channel('amqp')->info('User logged in', ['user_id' => 1]);
Log::channel('amqp')->error('Something broke!', ['exception' => $exception]);
```
Expected Log Structure
```json
{
    "timestamp": "2025-04-07 15:09:12",
    "env_mode": "production",
    "level": "info",
    "channel": "amqp",
    "source": "Test",
    "short_message": "User logged in",
    "context": {
        "user_id": 1
    },
    "server_ip": "127.0.0.1"
}
```

## ➕ Optional 

If you want to send your default log levels (e.g., error, info, debug) via this handler, you should install this package:

```bash
composer require anboz/custom-logger
```

##  License

This package is open-sourced software licensed under the MIT license.

