<?php

namespace Src\Alias;

class AliasLoader
{
    /**
     * The array of class aliases.
     *
     * @var array
     */
    protected $aliases;

    /**
     * The singleton instance of the loader.
     *
     * @var \Src\Alias\AliasLoader
     */
    protected static $instance;

    /**
     * Indicates if a loader has been registered.
     *
     * @var bool
     */
    protected $registered = false;

    /**
     * Create a new AliasLoader instance.
     *
     * @param  array  $aliases
     * @return void
     */
    private function __construct($aliases)
    {
        $this->aliases = $aliases;
    }

    /**
     * Get or create the singleton alias loader instance.
     *
     * @param  array  $aliases
     * @return \Src\Alias\AliasLoader
     */
    public static function getInstance(array $aliases = [])
    {
        if (is_null(static::$instance)) {
            return static::$instance = new static($aliases);
        }

        if ($aliases) {
            $aliases = array_merge(static::$instance->getAliases(), $aliases);

            static::$instance->setAliases($aliases);
        }

        return static::$instance;
    }

    /**
     * Get the registered aliases.
     *
     * @return array
     */
    public function getAliases()
    {
        return $this->aliases;
    }

    /**
     * Set the registered aliases.
     *
     * @param  array  $aliases
     * @return void
     */
    public function setAliases(array $aliases)
    {
        $this->aliases = $aliases;
    }

    /**
     * Register the loader on the auto-loader stack.
     *
     * @return void
     */
    public function register()
    {
        if (!$this->registered) {
            $this->prependToLoaderStack();

            $this->registered = true;
        }
    }

    /**
     * Prepend the load method to the auto-loader stack.
     *
     * @return void
     */
    protected function prependToLoaderStack()
    {
        spl_autoload_register([$this, 'load'], true, true);
    }

    /**
     * Load a class alias if it is registered.
     *
     * @param  string  $alias
     * @return bool|null
     */
    public function load($alias)
    {
        if (isset($this->aliases[$alias])) {
            return class_alias($this->aliases[$alias], $alias);
        }
    }
}