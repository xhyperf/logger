<?php

namespace XHyperf\LoggerPlus;

use Hyperf\Logger\LoggerFactory;
use Psr\Log\LoggerInterface;

class StdoutLoggerFactory
{
    public function __construct(protected LoggerFactory $factory)
    {
    }

    public function __invoke(): LoggerInterface
    {
        return $this->factory->get('STDOUT');
    }
}