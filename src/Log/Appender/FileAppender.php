<?php

namespace Src\Log\Appender;

class FileAppender extends AbstractorLoggerAppender
{
    public function info($data): bool
    {
        return $this->writeToFile($this->setType('INFO')->format($data));
    }

    public function warn($data): bool
    {
        return $this->writeToFile($this->setType('WARN')->format($data));;
    }

    public function debug($data): bool
    {
        return $this->writeToFile($this->setType('DEBUG')->format($data));;
    }
}