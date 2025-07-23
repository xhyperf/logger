<?php

declare(strict_types=1);

namespace XHyperf\LoggerPlus\Database;

use Hyperf\Collection\Arr;
use Hyperf\Context\Context;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Database\Events\QueryExecuted;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Stringable\Str;
use XHyperf\LoggerPlus\Log;

class DbQueryLog implements ListenerInterface
{
    use DbLogTrait;

    public function __construct(protected ConfigInterface $config)
    {
    }

    public function listen(): array
    {
        return [
            QueryExecuted::class,
        ];
    }

    public function process(object $event): void
    {
        if (! $event instanceof QueryExecuted) {
            return;
        }

        $sql = $event->sql;
        if (! Arr::isAssoc($event->bindings)) {
            foreach ($event->bindings as $value) {
                $sql = Str::replaceFirst(
                    '?',
                    sprintf("'%s'", is_string($value) ? str_replace('?', '@__u003f__@', $value) : $value),
                    $sql
                );
            }
        }

        $data = [
            'sql'        => str_replace('@__u003f__@', '?', $sql),
            'query_time' => $event->time,
            'idx'        => Context::override('@idx_sql', fn($v) => ++$v),
        ];

        if ($this->traceEnable()) {
            $data['trace'] = $this->getTrace();
        }

        Log::gather('sql', $data);
    }


}
