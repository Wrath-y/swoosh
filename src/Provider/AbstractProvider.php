<?php

namespace Src\Provider;

use Src\Support\Core;

/**
 * \Apps\Provider\AbstractProvider
 *
 * @package Apps\Provider
 */
abstract class AbstractProvider
{
    /**
     * The Service name.
     * @var string
     */
    protected $serviceName;

    protected $app;

    public function __construct(Core &$app)
    {
        $this->app = $app;
    }

    abstract public function register();
}
