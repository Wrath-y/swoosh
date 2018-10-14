<?php

namespace Src\Support;

class Core
{
    private $app;

    public function set($name, $definition)
    {
        $this->app[$name] = $definition;
    }

    public function get($name)
    {
        if ($this->app[$name] instanceof \Closure) {
            $this->app[$name] = $this->app[$name]();
        }

        return $this->app[$name];
    }
}
