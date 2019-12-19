<?php

namespace Src\Server\Log;

use Src\Support\App;
use Src\Server\Log\Appender\ConsoleAppender;
use Src\Server\Log\Appender\FileAppender;
use Src\Server\Log\Contract\AppenderInterface;

class LoggerManager
{
    /**
     * @var AppenderInterface
     */
    private $appender;
    private $enable = true;
    
    public function __construct(App $app)
    {
        $config = $this->app->get('config')->get('app.log_level');
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