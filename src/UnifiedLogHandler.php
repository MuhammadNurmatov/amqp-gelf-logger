<?php

namespace MuhammadN\AmqpGelfLogger;

use Monolog\Handler\HandlerInterface;
use Monolog\LogRecord;

class UnifiedLogHandler implements HandlerInterface
{
    protected $primaryHandler;
    protected $fallbackHandler;

    public function __construct(?HandlerInterface $primaryHandler, HandlerInterface $fallbackHandler)
    {
        $this->primaryHandler = $primaryHandler;
        $this->fallbackHandler = $fallbackHandler;
    }

    public function handle(LogRecord $record): bool
    {
        if ($this->primaryHandler && $this->primaryHandler->isHandling($record)) {
            try {
                return $this->primaryHandler->handle($record);
            } catch (\Exception | \Throwable $e) {
                (new AmqpGelfDefaultChannelHandler())->handle($e->getMessage());
            }
        }

        return $this->fallbackHandler->handle($record);
    }

    public function isHandling(LogRecord $record): bool
    {
        if ($this->primaryHandler && $this->primaryHandler->isHandling($record)) {
            return true;
        }

        return $this->fallbackHandler->isHandling($record);
    }

    public function handleBatch(array $records): void
    {
        if ($this->primaryHandler) {
            try {
                $this->primaryHandler->handleBatch($records);
                return;
            } catch (\Exception | \Throwable$e) {
                (new AmqpGelfDefaultChannelHandler())->handle($e->getMessage());
            }
        }

        $this->fallbackHandler->handleBatch($records);
    }

    public function close(): void
    {
        $this->primaryHandler?->close();
        $this->fallbackHandler->close();
    }
}
