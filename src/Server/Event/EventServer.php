<?php

namespace Src\Server\Event;

use Src\Support\App;

class EventServer
{
    public $app;
    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function __call($method, $parameters)
    {
        return $this->getDriver()->$method(...$parameters);
    }
}