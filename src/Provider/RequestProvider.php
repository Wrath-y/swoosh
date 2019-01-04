<?php

namespace Src\Provider;

use Src\App;
use Src\Server\RequestServer;

class RequestProvider extends AbstractProvider
{
    public function register()
    {
        $this->app->set('request', function () {
            return new RequestServer();
        });
    }
}