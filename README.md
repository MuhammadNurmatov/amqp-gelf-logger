# 📦 Laravel AMQP GELF Logger — Send Logs to Graylog via AMQP or GELF

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
# ⚙️ Configuration

### 1. Publish Config File

You can manually create the config file at `config/amqp-gelf-logger.php` or publish it via Artisan:

```bash
php artisan vendor:publish --tag=amqp-gelf-logger-config
```

### 2. Edit `config/amqp-gelf-logger.php`

```php
return [
    'rabbitmq' => [
        'host' => env('LOG_RABBITMQ_HOST', '127.0.0.1'),
        'port' => env('LOG_RABBITMQ_PORT', 5672),
        'user' => env('LOG_RABBITMQ_USER', 'guest'),
        'password' => env('LOG_RABBITMQ_PASSWORD', 'guest'),
        'exchange' => env('LOG_RABBITMQ_EXCHANGE', 'logs'),
        'exchange_type' => env('LOG_RABBITMQ_EXCHANGE_TYPE', 'direct'),
        'app_name' => env('APP_NAME', 'Laravel'),
        'app_env' => env('APP_ENV', 'local'),
        'level' => env('LOG_RABBITMQ_LEVEL', 'error'),
        'path' => storage_path('logs/amqp-gelf-logger.log'),
        'name' => 'amqp-gelf-logger',
        'day' => 14,
    ],
];
```

## 📡 Logging Channel Setup

Add a custom logging channel in `config/logging.php`:
```php
'amqp' => [
    'driver' => 'custom',
    'via' => \MuhammadN\AmqpGelfLogger\RabbitMQLogger::class,
    'routeing_key' => 'graylog',
    'queue_name' => 'graylog',
    'name' => 'graylog',
    'level' => 'debug',
    'path' => storage_path('logs/graylog.log'),
    'days' => 14,
],
```
## 🧪 Usage

You can use the custom logger like any other Laravel log channel:

```php
use Illuminate\Support\Facades\Log;

Log::channel('amqp')->info('User logged in', ['user_id' => 1]);
Log::channel('amqp')->error('Something broke!', ['exception' => $exception]);
```
## ➕ Optional Addon

If you want to set your default channel via this handler, you should install this package:

```bash
composer require anboz/custom-logger
```

## 📄 License

This package is open-sourced software licensed under the MIT license.

