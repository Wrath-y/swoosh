<?php

namespace Src\Event;

use Src\Core\App;

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