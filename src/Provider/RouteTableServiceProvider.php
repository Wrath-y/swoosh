<?php

namespace Src\Provider;

use Src\App;
use Src\Server\RouteTableServer;

class RouteTableServiceProvider extends AbstractProvider
{
    public function register()
    {
        $this->app->set('routeTable', function () {
            return new RouteTableServer();
        });
    }
}