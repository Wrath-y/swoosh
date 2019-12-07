<?php

namespace Src\Server\Log;

use Src\Server\Log\Appender\ConsoleAppender;
use Src\Server\Log\Appender\FileAppender;
use Src\Server\Log\Contract\AppenderInterface;

class LoggerManage
{
    /**
     * @var AppenderInterface
     */
    private $appender;
    private $enable = true;
    
    public function __construct(array $config)
    {
        $this->enable = $config['enable'];

        if (!$this->enable) {
            return;
        }

        switch ($config['level']) {
            case 'debug':
                $this->appender = new ConsoleAppender($config);
                break;
            default:
                $this->appender = new FileAppender($config);
                break;
        }
    }

    /**
     * @return AppenderInterface
     */
    protected function getAppender()
    {
        return $this->appender;
    }

    public function __call($method, $parameters)
    {
        if (!$this->enable) {
            return false;
        }
        
        return $this->getAppender()->{$method}(...$parameters);
    }
}