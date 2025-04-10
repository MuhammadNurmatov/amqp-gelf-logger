<?php

namespace MuhammadN\AmqpGelfLogger;

use Monolog\Formatter\JsonFormatter;
use Monolog\LogRecord;
use Throwable;

class AmqpGelfLoggerFormater extends JsonFormatter
{
    public function __construct()
    {
        parent::__construct();
    }

    private static ?string $serverIP = null;

    public function format(LogRecord $record): string
    {
        $formatted = $this->formatRecord($record);
        return $this->toJson($formatted) . ($this->appendNewline ? "\n" : '');
    }

    public function formatRecord(LogRecord $record): array
    {
        $context = $record->context;
        if (isset( $record->context['exception']) &&  $record->context['exception'] instanceof Throwable) {
            $e =  $record->context['exception'];
            $context['exception'] =  $e->getTraceAsString();
        }


        return [
            'timestamp' => $record->datetime->format('Y-m-d H:i:s'),
            'env_mode' => config('app.env'),
            'level' => $record->level->getName(),
            'channel' => $record->channel,
            'source' => config('app.name'),
            'short_message' => $record->message,
            'context' => $context,
            'server_ip' => $this->initServerIP(),
        ];
    }

    private function initServerIP(): ?string
    {
        if (self::$serverIP == null) {
            self::$serverIP = trim(shell_exec("hostname -I | awk '{print $1}'"));
        }

        return self::$serverIP;
    }
}
