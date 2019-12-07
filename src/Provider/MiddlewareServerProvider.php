<?php

namespace Src\Provider;

class MiddlewareServerProvider extends AbstractProvider
{
    public function register()
    {
        $this->app->set('middleware', function () {
            return new MiddlewareServer();
        });
    }
}
