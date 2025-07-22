<?php

declare(strict_types=1);

use Monolog\Handler\StreamHandler;
use Monolog\Level;
use XHyperf\LoggerPlus\Formatter\DockerFluentFormatter;
use XHyperf\LoggerPlus\Formatter\LineFormatter;
use XHyperf\LoggerPlus\Formatter\StdoutFormatter;
use XHyperf\LoggerPlus\Handler\StdoutHandler;
use XHyperf\LoggerPlus\Log;

use function Hyperf\Support\env;

$fluentFormatter = [
    'class'       => DockerFluentFormatter::class,
    'constructor' => [
        'levelTag' => true,
    ],
];

$lineFormatter = [
    'class'       => LineFormatter::class,
    'constructor' => [
        'format'                => null,
        'dateFormat'            => 'Y-m-d H:i:s',
        'allowInlineLineBreaks' => true,
    ],
];

$fileHandler = [
    'class'       => StreamHandler::class,
    'constructor' => [
        'stream' => BASE_PATH . '/runtime/logs/hyperf.log',
        'level'  => Level::Debug,
    ],
];

$handlers = [];

// 输出到文件
if (Log::isOutputFile()) {
    $handlers[] = [
        ...$fileHandler,
        'formatter' => Log::isOutputFluent() ? $fluentFormatter : $lineFormatter,
    ];
}

// 输出到控制台
if (Log::isOutputConsole()) {
    $handlers[] = [
        'class'       => StdoutHandler::class,
        'constructor' => [
            'level' => env('LOG_LEVEL', Level::Debug->value),
        ],
        'formatter'   => Log::isOutputFluent()
            ? $fluentFormatter
            : [
                'class'       => StdoutFormatter::class,
                'constructor' => [
                    'allowInlineLineBreaks' => true,
                ],
            ],
    ];
}

return [
    'default' => [
        'handlers'  => $handlers ?: [[]],
        'handler'   => $fileHandler,
        'formatter' => $lineFormatter,
    ],
];
