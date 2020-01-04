<?php

namespace Src\Log\Appender;

class ConsoleAppender extends AbstractorLoggerAppender
{
    public function info($data): bool
    {
        return (bool)print_r($this->setType('INFO')->format($data));
    }

    public function warn($data): bool
    {
        return (bool)print_r($this->setType('WARN')->format($data));
    }

    public function debug($data): bool
    {
        return (bool)print_r($this->setType('DEBUG')->format($data));
    }
}