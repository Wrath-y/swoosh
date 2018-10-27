<?php

namespace Src\Provider;

use Src\App;
use Src\Server\RouteTableServer;

class RouteTableServiceProvider extends AbstractProvider
{
    protected $serviceName = 'routeTableService';

    public function register()
    {
        new RouteTableServer();
        $this->app->set($this->serviceName, function () {
            return new RouteTableServer();
        });
    }
}