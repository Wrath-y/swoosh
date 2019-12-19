<?php

namespace Src\Server\Event;

use Src\Support\App;

class EventServer
{
    public static $app;
    public function __construct(App $app)
    {
        self::$app = $app;
    }

    public function __call($method, $parameters)
    {
        return $this->getDriver()->$method(...$parameters);
    }
}