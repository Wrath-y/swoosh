<?php

namespace Src\Provider;

use Src\App;
use Src\Server\RequestServer;

class RequestProvider extends AbstractProvider
{
    protected $serviceName = 'request';

    public function register()
    {
        $this->app->set($this->serviceName, function () {
            return new RequestServer();
        });
    }
}