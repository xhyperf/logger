<?php

namespace XHyperf\LoggerPlus\Formatter;

use Monolog\Formatter\LineFormatter;
use Monolog\Level;
use Monolog\LogRecord;
use XHyperf\LoggerPlus\Log;

/**
 * 标准输出格式器，用于开发环境输出到控制台
 */
class StdoutFormatter extends LineFormatter
{
    public const string SIMPLE_FORMAT = "<fg=cyan>[%datetime%]</> %extra.request_id% <%extra.level_tag%>[%channel%.%extra.level_name%]</> <fg=yellow>%message%</> %context%\n";

    public const string SIMPLE_DATE = "Y-m-d H:i:s.u";

    public function format(LogRecord $record): string
    {
        if ($record['channel'] === 'GATHER' && $record['message'] === 'gather') {
            $record = $record->with(
                message: "\e[1D",
                context: $record['context']['data'] ?? $record['context'],
                level  : Level::Info,
                extra  : ['level_name' => $record['context']['tag'] ?? $record->level->getName()]
            );
        }

        $record->extra['level_tag'] = match ($record->level) {
            Level::Emergency, Level::Alert, Level::Critical => 'error',
            Level::Error => 'fg=red',
            Level::Warning, Level::Notice => 'comment',
            default => 'info',
        };

        $record->extra['level_name'] ??= $record->level->getName();
        $record->extra['request_id'] = Log::getRequestId() ?: "\e[1D";

        return parent::format($record);
    }
}