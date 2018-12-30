<?php

namespace Src\Support;

use ReflectionClass;
use Src\Foundation\AliasLoader;


class Core
{
    private $app;

    private $aliases;

    /**
     * Indicates if the application has been bootstrapped before.
     *
     * @var bool
     */
    protected $hasBeenBootstrapped = false;

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

    /**
     * Run the given array of bootstrap classes.
     *
     * @param  array  $bootstrappers
     * @return void
     */
    public function bootstrapWith(array $bootstrappers)
    {
        $this->hasBeenBootstrapped = true;

        foreach ($bootstrappers as $bootstrapper) {
            $this->make($bootstrapper)->bootstrap($this);
        }
    }

    /**
     * Resolve the given type from the container.
     *
     * @param  string  $abstract
     * @param  array  $parameters
     * @return mixed
     */
    public function make($abstract)
    {
        return $this->resolve($abstract);
    }

    /**
     * Resolve the given type from the container.
     *
     * @param  string  $abstract
     * @param  array  $parameters
     * @return mixed
     */
    protected function resolve($abstract)
    {
        $abstract = $this->getAlias($abstract);

        // If an instance of the type is currently being managed as a singleton we'll
        // just return an existing instance instead of instantiating new instances
        // so the developer can keep using the same objects instance every time.
        if (isset($this->app[$abstract])) {
            return $this->app[$abstract];
        } else {
            return $this->app[$abstract] = $this->build($abstract);
        }
    }

    /**
     * Instantiate a concrete instance of the given type.
     *
     * @param  string  $abstract
     * @return mixed
     *
     */
    public function build(string $abstract)
    {
        $reflector = new ReflectionClass($abstract);

        // If the type is not instantiable, the developer is attempting to resolve
        // an abstract type such as an Interface of Abstract Class and there is
        // no binding registered for the abstractions so we need to bail out.
        if (!$reflector->isInstantiable()) {
            throw new Exception("[{$abstract}] can not instantiable");
        }

        return new $abstract;
    }

    /**
     * Get the alias for an abstract if available.
     *
     * @param  string  $abstract
     * @return string
     *
     * @throws \Exception
     */
    public function getAlias($abstract)
    {
        if (!$this->aliases) {
            $this->aliases = AliasLoader::getInstance()->getAliases();
        }

        if (!isset($this->aliases[$abstract])) {
            return $abstract;
        }

        if ($this->aliases[$abstract] === $abstract) {
            throw new Exception("[{$abstract}] is aliased to itself.");
        }

        return $this->getAlias($this->aliases[$abstract]);
    }

    /**
     * Determine if the application has been bootstrapped before.
     *
     * @return bool
     */
    public function hasBeenBootstrapped()
    {
        return $this->hasBeenBootstrapped;
    }

    public function app()
    {
        return $this->app;
    }
}
