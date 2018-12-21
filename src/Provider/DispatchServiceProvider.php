<?php

namespace Src\Provider;

use Src\App;
use Src\Server\DispatcherServer;

class DispatchServiceProvider extends AbstractProvider
{
    protected $serviceName = 'dispatcher';

    public function register()
    {
        $this->app->set($this->serviceName, function () {
            return new DispatcherServer($this->app);
        });
    }
}