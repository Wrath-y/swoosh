<?php

namespace App\Services;

abstract class Service
{
    public static function __callStatic($method, $arguments)
    {
        return (new static)->$method(...$arguments);
    }
}
