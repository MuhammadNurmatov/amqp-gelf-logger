<?php

namespace MuhammadN\AmqpGelfLogger\Services;

use MuhammadN\AmqpGelfLogger\Contracts\AmqpGelfServiceContract;
use PhpAmqpLib\Connection\AMQPSSLConnection;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQService implements AmqpGelfServiceContract
{

    private  AMQPStreamConnection|AMQPSSLConnection|null $connection = null;
    private ?AMQPChannel $channel = null;

    public ?array $config;



    public function __construct(array $config)
    {
        $this->config = $config;
        $this->initConnection();
        $this->initChannel();
    }

    public function send(mixed $message): void
    {
        $this->publish($message);
    }


    private function initConnection(): void
    {
            if (isset($this->config['use_tls']) && $this->config['use_tls'] === true) {
                $this->makeSSLConnection();
            } else {
                $this->makeStreamConnection();
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
        $this->connection = new AMQPSSLConnection(
            $this->config['host'],
            $this->config['port'],
            $this->config['user'],
            $this->config['password'],
            $this->config['vhost'],
            $sslOptions,
        );
    }

    /**
     * @throws \Exception
     */
    private function makeStreamConnection(): void
    {
        $this->connection = new AMQPStreamConnection(
            $this->config['host'],
            $this->config['port'],
            $this->config['user'],
            $this->config['password'],
            $this->config['vhost'],
        );
    }

    private function initChannel(): void
    {
        $this->connection->channel();
    }

    private function publish(mixed $message): void
    {
        $msg = new AMQPMessage(
            $message,
            [
                'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT,
                'content_type' => 'application/json'
            ]
        );

        $routingKey = $this->config['routing_key'];
        $this->channel->basic_publish($msg, $this->config['exchange'], $routingKey);
    }

    public function __destruct()
    {
        if ($this->channel !== null) {
            $this->channel->close();
            $this->channel = null;
        }

        if ($this->connection !== null) {
            $this->connection->close();
            $this->connection = null;
        }
    }
}
