<?php

namespace Src\Provider;

use Src\Server\Log\LoggerManage;

class LogServiceProvider extends AbstractProvider
{
    public function register()
    {
        $this->app->set('log', function () {
            return (new LoggerManage($this->app->get('config')->get('app.log_level')));
        });
    }
}
