<?php

namespace XHyperf\LoggerPlus\Handler;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Level;
use Monolog\LogRecord;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

class StdoutHandler extends AbstractProcessingHandler
{
    protected OutputInterface $output;

    /**
     * @param int|string|Level $level 错误级别
     * @param bool $bubble 是否处理其他 handler 的日志
     */
    public function __construct(int|string|Level $level = Level::Debug, bool $bubble = true)
    {
        parent::__construct($level, $bubble);
        $this->output = new ConsoleOutput();
    }

    protected function write(LogRecord $record): void
    {
        $this->output->write($record['formatted']);
    }
}