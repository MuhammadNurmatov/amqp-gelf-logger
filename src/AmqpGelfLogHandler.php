<?php

namespace AmqpGelfLogger;

use Illuminate\Support\Facades\Log;
use Monolog\Handler\HandlerInterface;
use Monolog\LogRecord;
use PhpAmqpLib\Exception\AMQPIOException;

class AmqpGelfLogHandler implements HandlerInterface
{
    protected $primaryHandler;
    protected $fallbackHandler;

    public function __construct(HandlerInterface $primaryHandler, HandlerInterface $fallbackHandler)
    {
        $this->primaryHandler = $primaryHandler;
        $this->fallbackHandler = $fallbackHandler;
    }

    public function handle(LogRecord $record): bool
    {
        try {
            return $this->primaryHandler->handle($record);
        } catch (AMQPIOException $e) {
            Log::channel('daily')->error($e->getMessage());
        }

        return $this->fallbackHandler->handle($record);
    }

    public function isHandling(LogRecord $record): bool
    {
        return $this->primaryHandler->isHandling($record);
    }

    public function handleBatch(array $records): void
    {
        try {
            $this->primaryHandler->handleBatch($records);
        } catch (\Exception $e) {
            $this->fallbackHandler->handleBatch($records);
        }
    }

    public function close(): void
    {
        $this->primaryHandler->close();
        $this->fallbackHandler->close();
    }
}
