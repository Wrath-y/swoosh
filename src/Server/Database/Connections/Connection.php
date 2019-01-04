<?php

namespace Src\Server\Database\Connections;

use Src\Server\Database\ConnectionInterface;

class Connection implements ConnectionInterface
{
    /**
     * The active PDO connection.
     *
     * @var \PDO|\Closure
     */
    protected $pdo;

    /**
     * The name of the connected database.
     *
     * @var string
     */
    protected $database;

    /**
     * The table prefix for the connection.
     *
     * @var string
     */
    protected $tablePrefix = '';

    /**
     * The database connection configuration options.
     *
     * @var array
     */
    protected $config = [];

    /**
     * Create a new database connection instance.
     *
     * @param  \PDO|\Closure     $pdo
     * @param  string   $database
     * @param  string   $tablePrefix
     * @param  array    $config
     * @return void
     */
    public function __construct($pdo, $database = '', $tablePrefix = '', array $config = [])
    {
        $this->pdo = $pdo;

        $this->database = $database;

        $this->tablePrefix = $tablePrefix;

        $this->config = $config;

        $this->useDefaultQueryGrammar();

        $this->useDefaultPostProcessor();
    }

    /**
     * Set the query grammar to the default implementation.
     *
     * @return void
     */
    public function useDefaultQueryGrammar()
    {
        $this->queryGrammar = $this->getDefaultQueryGrammar();
    }

    /**
     * Get the default query grammar instance.
     *
     * @return \Src\Server\Database\Query\Grammars\Grammar
     */
    protected function getDefaultQueryGrammar()
    {
        return new QueryGrammar;
    }

    /**
     * Get the connection resolver for the given driver.
     *
     * @param  string  $driver
     * @return mixed
     */
    public static function getResolver($driver)
    {
        return static::$resolvers[$driver] ?? null;
    }

    /**
     * Get the query grammar used by the connection.
     *
     * @return \Src\Server\Database\Query\Grammars\Grammar
     */
    public function getQueryGrammar()
    {
        return $this->queryGrammar;
    }

    /**
     * Get the query post processor used by the connection.
     *
     * @return \Src\Server\Database\Query\Processors\Processor
     */
    public function getPostProcessor()
    {
        return $this->postProcessor;
    }
}
