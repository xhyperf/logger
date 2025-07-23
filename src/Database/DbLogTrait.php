<?php

declare(strict_types=1);

namespace XHyperf\LoggerPlus\Database;

use XHyperf\LoggerPlus\ConfigKey;

use function Hyperf\Config\config;
use function Hyperf\Support\env;

trait DbLogTrait
{
    /**
     * 是否开启 SQL 跟踪日志
     * @return bool
     */
    protected function traceEnable(): bool
    {
        return config(ConfigKey::SQL_TRACE_ENABLE, env('LOG_SQL_TRACE', false));
    }

    /**
     * 获取 SQL 跟踪信息
     * @return array
     */
    protected function getTrace(): array
    {
        foreach (debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS) as $trace) {
            if (! str_starts_with($trace['file'] ?? '', BASE_PATH . '/vendor')) {
                return $trace;
            }
        }

        return [];
    }
}