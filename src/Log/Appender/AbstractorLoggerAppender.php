<?php

namespace Src\Log\Appender;

use Src\App;
use Src\Log\LogFormatter;
use Src\Log\Contract\AppenderInterface;

abstract class AbstractorLoggerAppender implements AppenderInterface
{
    protected $log_level;

    /**
     * Log File Dir
     */
    protected $log_dir = '';

    /**
     * Log Type
     */
    protected $type = 'INFO';

    public function __construct(array $config)
    {
        $this->log_level = $config['level'];
        $this->log_dir = $config['log_dir'];
    }

    public function setType(string $type)
    {
        $this->type = $type;

        return $this;
    }

    public function format($data): string
    {
        return date('Y-m-d') . ' ' . $this->type . ' ' . LogFormatter::format($data);
    }

    public function writeToFile($data): bool
    {
        $dir = App::getPath().$this->log_dir;
        if (is_dir($dir)) {
            return (bool)file_put_contents($dir.date('Y-m-d').'.log', $data . "\n", FILE_APPEND | LOCK_EX);
        }
        mkdir($dir, 0777, true);

        return (bool)file_put_contents($dir.date('Y-m-d').'.log', $data . "\n", FILE_APPEND | LOCK_EX);
    }
}
