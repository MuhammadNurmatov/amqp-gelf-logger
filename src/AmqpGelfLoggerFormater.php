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
        if (self::$serverIP === null) {
            $ip = '';
            if (PHP_OS_FAMILY === 'Linux' && function_exists('shell_exec')) {
                $ip = trim((string) shell_exec("hostname -I 2>/dev/null | awk '{print $1}'"));
            }

            self::$serverIP = $ip !== '' ? $ip : self::outboundIP();
        }

        return self::$serverIP;
    }

    // UDP connect sends no packets, it only makes the OS pick the outgoing interface
    private static function outboundIP(): ?string
    {
        $socket = @socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
        if ($socket === false) {
            return null;
        }

        $ip = null;
        if (@socket_connect($socket, '8.8.8.8', 53) && socket_getsockname($socket, $addr)) {
            $ip = $addr;
        }
        socket_close($socket);

        return $ip;
    }
}
