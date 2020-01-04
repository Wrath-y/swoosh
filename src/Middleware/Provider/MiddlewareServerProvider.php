<?php

namespace Src\Middleware\Provider;

use Src\Core\AbstractProvider;
use Src\Middleware\MiddlewareServer;

class MiddlewareServerProvider extends AbstractProvider
{
    public function register()
    {
        $this->app->set('middleware', function () {
            return new MiddlewareServer();
        });
    }
}
