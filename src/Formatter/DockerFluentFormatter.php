<?php

namespace XHyperf\LoggerPlus\Formatter;

use DateTimeInterface;
use Monolog\Level;
use Monolog\Utils;

/**
 * Docker 环境下的日志格式器，用于将日志打印到 docker 标准输出
 */
class DockerFluentFormatter extends FluentFormatter
{
    public function pack(string $tag, Level $level, DateTimeInterface $time, array $data): string
    {
        $data['__level__'] = $data['__level__'] ?? $level->toPsrLogLevel();
        $data['__tag__']   = $tag;
        $data['__time__']  = $time->format('Y-m-d H:i:s');

        return Utils::jsonEncode($data) . ($this->appendNewline ? "\n" : '');
    }
}