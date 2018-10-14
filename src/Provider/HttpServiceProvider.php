<?php

namespace Src\Provider;

use Src\App;
use Src\Server\HttpServer;

class HttpServiceProvider extends AbstractProvider
{
    protected $serviceName = 'http';

    public function register()
    {
        $this->app->set($this->serviceName, function () {
            $server = App::getApp()->get('config')->get('server');
            return new HttpServer($server['host'], $server['port']);
        });
    }
}
