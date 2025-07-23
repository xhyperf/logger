<?php

declare(strict_types=1);

namespace XHyperf\LoggerPlus;

use Hyperf\Contract\StdoutLoggerInterface;
use XHyperf\LoggerPlus\Database\DbQueryLog;
use XHyperf\LoggerPlus\Database\DbTransactionLog;
use XHyperf\LoggerPlus\Guzzle\GuzzleLogAspect;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                StdoutLoggerInterface::class => StdoutLoggerFactory::class,
            ],
            'listeners'    => [
                DbQueryLog::class,
                DbTransactionLog::class,
            ],
            'annotations'  => [
                'scan' => [
                    'collectors' => [
                    ],
                ],
            ],
            'aspects'      => [
                GuzzleLogAspect::class,
            ],
            'publish'      => [
                [
                    'id'          => 'config',
                    'description' => 'The config for logger.',
                    'source'      => __DIR__ . '/../publish/logger.php',
                    'destination' => BASE_PATH . '/config/autoload/logger.php',
                ],
            ],
        ];
    }
}
