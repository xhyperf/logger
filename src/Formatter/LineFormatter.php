<?php

namespace XHyperf\LoggerPlus\Formatter;

use Monolog\Level;
use Monolog\LogRecord;
use XHyperf\LoggerPlus\Log;

/**
 * 行格式器
 */
class LineFormatter extends \Monolog\Formatter\LineFormatter
{
    public const string SIMPLE_FORMAT = "[%datetime%] %extra.request_id% [%channel%.%extra.level_name%] %message% %context%\n";

    public const string SIMPLE_DATE = "Y-m-d H:i:s.u";

    public function format(LogRecord $record): string
    {
        if ($record['channel'] === 'GATHER' && $record['message'] === 'gather') {
            $record = $record->with(
                message: "\e[1D",
                context: $record['context']['data'] ?? $record['context'],
                level: Level::Info,
                extra: ['level_name' => $record['context']['tag'] ?? $record->level->getName()]
            );
        }

        $record->extra['request_id'] = Log::getRequestId() ?: "\e[1D";

        return parent::format($record);
    }
}