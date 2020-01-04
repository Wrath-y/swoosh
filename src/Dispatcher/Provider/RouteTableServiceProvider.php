<?php

namespace Src\Dispatcher\Provider;

use Src\Core\AbstractProvider;
use Src\Dispatcher\RouteTableServer;

class RouteTableServiceProvider extends AbstractProvider
{
    public function register()
    {
        $this->app->set('route_table', function () {
            return new RouteTableServer();
        });
    }
}