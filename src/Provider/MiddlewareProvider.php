<?php

namespace Src\Provider;

use Src\App;
use Src\Server\MiddlewareServer;

class MiddlewareProvider extends AbstractProvider
{
    public function register()
    {
        $this->app->set('middleware', function () {
            return new MiddlewareServer();
        });
    }
}
