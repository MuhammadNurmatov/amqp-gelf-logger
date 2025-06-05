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
     'transport' => env('AMQP_GELF_TRANSPORT', 'tcp'),

    'level' => env('RABBITMQ_LOG_LEVEL', 'debug'),
    'path' => storage_path('logs/amqp-gelf-logger/logs.log'),
    'days' => 14,

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
    ],

    'udp' => [
        'host' => env('LOG_UDP_HOST', '127.0.0.1'),
        'port' => env('LOG_UDP_PORT', 555),
        'max_buffer' => env('LOG_UDP_MAX_BUFFER', 1024*20), //20kb
    ],

    'tcp' => [
        'host' => env('LOG_UDP_HOST', '127.0.0.1'),
        'port' => env('LOG_UDP_PORT', 555),
    ]
];
```
 **transport** - the driver to use for sending messages

If you don't want to use SSL, set **use_tls = false** in the config.

##  Logging Channel Setup

Add a custom logging channel in `config/logging.php`:
```php
'amqp' => [
    'driver' => 'custom',
    'via' => \MuhammadN\AmqpGelfLogger\AmqpGelfLogger::class,
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

