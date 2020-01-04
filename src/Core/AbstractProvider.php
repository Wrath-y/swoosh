<?php

namespace Src\Core;

use Src\Core\App;

abstract class AbstractProvider
{
    /**
     * The Service name.
     * @var string
     */
    protected $serviceName;

    protected $app;

    public $table;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    abstract public function register();
}
