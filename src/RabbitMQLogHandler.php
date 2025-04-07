<?php

namespace MuhammadN\AmqpGelfLogger;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\LogRecord;
use PhpAmqpLib\Connection\AMQPSSLConnection;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQLogHandler extends AbstractProcessingHandler
{
    private static AMQPStreamConnection|AMQPSSLConnection|null $connection = null;
    public ?array $config;
    public ?array $logConfig;
    public function __construct(array $logConfig) {

        parent::__construct($logConfig['level']);

       $this->config = config('amqp-gelf-logger.rabbitmq');
       $this->logConfig = $logConfig;
    }

    protected function write(LogRecord $record): void
    {
        $this->initializeRabbitMQ();
        $this->publish($record);
    }

    private function initializeRabbitMQ(): void
    {
        if (self::$connection === null || !self::$connection->isConnected()) {
            if (isset($this->config['use_tls']) && $this->config['use_tls'] === true) {
                $this->makeSSLConnection();
            } else {
                $this->makeStreamConnection();
            }
        }
    }

    /**
     * @throws \Exception
     */
    private function makeSSLConnection(): void
    {

        $sslOptions = [
            'verify_peer' => $this->config['verify_peer'],
            'verify_peer_name' => $this->config['verify_peer_name'],
            'cafile' => $this->config['cafile'],
            'local_cert' => $this->config['local_cert'],
            'local_pk' => $this->config['local_pk'],
        ];
        self::$connection = new AMQPSSLConnection(
            $this->config['host'],
            $this->config['port'],
            $this->config['user'],
            $this->config['password'],
            $this->config['vhost'],
            $sslOptions,
        );
    }


    private function makeStreamConnection(): void
    {
        self::$connection = new AMQPStreamConnection(
            $this->config['host'],
            $this->config['port'],
            $this->config['user'],
            $this->config['password'],
            $this->config['vhost'],
        );
    }


    private function publish(LogRecord $record): void
    {
        $channel = self::$connection->channel();
        $formatted = $this->getFormatter()->format($record);

        $msg = new AMQPMessage(
            $formatted,
            [
                'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT,
                'content_type' => 'application/json'
            ]
        );

        $routingKey = $this->config['routing_key'] ?? $record->channel;
        $channel->basic_publish($msg, $this->config['exchange'], $routingKey);

        $channel->close();
    }

    public function __destruct()
    {
        if (self::$connection !== null) {
            self::$connection->close();
            self::$connection = null;
        }
    }
}
