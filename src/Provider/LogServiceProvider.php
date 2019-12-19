<?php

namespace Src\Provider;

use Src\Server\Log\LoggerManager;

class LogServiceProvider extends AbstractProvider
{
    public function register()
    {
        $this->app->set('log', function () {
            return (new LoggerManager($this->app));
        });
    }
}
