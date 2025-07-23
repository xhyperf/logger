<?php

declare(strict_types=1);

namespace XHyperf\LoggerPlus\Database;

use Hyperf\Database\Events\TransactionBeginning;
use Hyperf\Database\Events\TransactionCommitted;
use Hyperf\Database\Events\TransactionRolledBack;
use Hyperf\Event\Contract\ListenerInterface;
use XHyperf\LoggerPlus\Log;

class DbTransactionLog implements ListenerInterface
{
    use DbLogTrait;

    public function listen(): array
    {
        return [
            TransactionBeginning::class,
            TransactionCommitted::class,
            TransactionRolledBack::class,
        ];
    }

    /**
     * @param object $event
     */
    public function process(object $event): void
    {
        $data = [
            'sql' => match (true) {
                $event instanceof TransactionBeginning => 'beginTransaction',
                $event instanceof TransactionCommitted => 'commit',
                $event instanceof TransactionRolledBack => 'rollBack',
            },
        ];

        if ($event instanceof TransactionRolledBack || $this->traceEnable()) {
            $data['trace'] = $this->getTrace();
        }

        Log::gather('sql', $data);
    }
}
