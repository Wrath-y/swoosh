<?php

namespace Src\Provider;

use Src\Support\App;

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

    public $table;

    public function __construct(App &$app)
    {
        $this->app = $app;
    }

    abstract public function register();
}
