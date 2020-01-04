<?php

namespace Src\Log\Provider;

use Src\Log\LoggerManager;
use Src\Core\AbstractProvider;

class LogServiceProvider extends AbstractProvider
{
    public function register()
    {
        $this->app->set('log', function () {
            return (new LoggerManager($this->app));
        });
    }
}
