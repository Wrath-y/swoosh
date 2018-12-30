<?php

namespace Src\Provider;

use Src\App;
use Src\Server\MiddlewareServer;

class MiddlewareProvider extends AbstractProvider
{
    protected $serviceName = 'middleware';

    public function register()
    {
        $this->app->set($this->serviceName, function () {
            return new MiddlewareServer();
        });
    }
}
