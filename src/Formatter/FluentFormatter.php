<?php

declare(strict_types=1);

namespace XHyperf\LoggerPlus\Formatter;

use DateTimeInterface;
use Monolog\Formatter\FormatterInterface;
use Monolog\Level;
use Monolog\LogRecord;
use Monolog\Utils;
use RuntimeException;
use XHyperf\LoggerPlus\Log;

/**
 * Fluentd 格式器
 */
class FluentFormatter implements FormatterInterface
{
    public function __construct(protected bool $levelTag = false, protected bool $appendNewline = true)
    {
        if (! function_exists('json_encode')) {
            throw new RuntimeException('PHP\'s json extension is required to use Monolog\'s FluentdUnixFormatter');
        }
    }

    public function isUsingLevelsInTag(): bool
    {
        return $this->levelTag;
    }

    public function format(LogRecord $record): string
    {
        $tag = strtolower($record['channel']);
        if ($this->levelTag) {
            $tag .= '.' . strtolower($record['level_name']);
        }

        $msg = [
            'message' => $record['message'],
            'context' => $record['context'],
        ];

        if ($record['channel'] === 'GATHER' && $record['message'] === 'gather') {
            $msg = $record['context']['data'] ?? $record['context'];
            $tag = 'gather.' . ($record['context']['tag'] ?? 'gather');
        }

        if (! $this->levelTag) {
            $msg['level']      = $record['level'];
            $msg['level_name'] = $record['level_name'];
        }

        if ($requestId = Log::getRequestId()) {
            $msg['request_id'] = $requestId;
        }

        return $this->pack($tag, $record->level, $record['datetime'], $msg);
    }

    /**
     * @param string            $tag
     * @param Level             $level
     * @param DateTimeInterface $time
     * @param array             $data
     * @return string
     */
    public function pack(string $tag, Level $level, DateTimeInterface $time, array $data): string
    {
        return Utils::jsonEncode([$tag, $time->getTimestamp(), $data]) . ($this->appendNewline ? "\n" : '');
    }

    public function formatBatch(array $records): string
    {
        $message = '';
        foreach ($records as $record) {
            $message .= $this->format($record);
        }

        return $message;
    }
}