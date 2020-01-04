<?php

namespace Src\Database\Connections;

use PDO;
use Closure;
use Src\App;
use Exception;
use PDOException;
use PDOStatement;
use Swoole\Coroutine\MySQL\Statement as CoStatement;
use Src\Database\Query\Builder;
use Src\Database\TransactionManager;
use Src\Database\ConnectionInterface;
use Src\Database\Query\Grammars\Grammar;
use Src\Database\Query\Processors\Processor;
use Src\Core\Contexts\DBContext;

class Connection implements ConnectionInterface
{
    use TransactionManager;

    /**
     * The active PDO connection.
     *
     * @var \PDO|\Closure
     */
    public $pdo;

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
     * The reconnector instance for the connection.
     *
     * @var callable
     */
    protected $reconnector;

    /**
     * The query grammar implementation.
     *
     * @var \Src\Database\Query\Grammars\Grammar
     */
    protected $queryGrammar;

    /**
     * The number of active transactions.
     *
     * @var int
     */
    protected $transactions = 0;

    /**
     * Indicates if changes have been made to the database.
     *
     * @var int
     */
    protected $recordsModified = false;

    /**
     * The default fetch mode of the connection.
     *
     * @var int
     */
    protected $fetchMode = PDO::FETCH_OBJ;

    protected $is_pool = false;

    /**
     * Create a new database connection instance.
     *
     * @param  \PDO|\Closure     $pdo
     * @param  string   $database
     * @param  string   $tablePrefix
     * @return void
     */
    public function __construct($pdo, $config, $database = '', $tablePrefix = '')
    {
        $this->pdo = $pdo;

        $this->database = $database;

        $this->tablePrefix = $tablePrefix;

        $this->config = $config;

        $this->is_pool = $config['mode'] === 'pool';

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
     * @return \Src\Database\Query\Grammars\Grammar
     */
    protected function getDefaultQueryGrammar()
    {
        return new Grammar;
    }

    /**
     * Set the query post processor to the default implementation.
     *
     * @return void
     */
    public function useDefaultPostProcessor()
    {
        $this->postProcessor = $this->getDefaultPostProcessor();
    }

    /**
     * Get the default post processor instance.
     *
     * @return \Src\Database\Query\Processors\Processor
     */
    protected function getDefaultPostProcessor()
    {
        return new Processor;
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
     * @return \Src\Database\Query\Grammars\Grammar
     */
    public function getQueryGrammar()
    {
        return $this->queryGrammar;
    }

    /**
     * Get the query post processor used by the connection.
     *
     * @return \Src\Database\Query\Processors\Processor
     */
    public function getPostProcessor()
    {
        return $this->postProcessor;
    }

    /**
     * Begin a fluent query against a database table.
     *
     * @param  string  $table
     * @return \Src\Database\Query\Builder
     */
    public function table($table)
    {
        return $this->query()->from($table);
    }

    /**
     * Get a new query builder instance.
     *
     * @return \Src\Database\Query\Builder
     */
    public function query()
    {
        return new Builder(
            $this,
            $this->getQueryGrammar(),
            $this->getPostProcessor()
        );
    }

    /**
     * Run a SQL statement and log its execution context.
     *
     * @param  string    $query
     * @param  array     $bindings
     * @param  \Closure  $callback
     * @return mixed
     */
    protected function run($query, $bindings, Closure $callback)
    {
        $this->reconnectIfMissingConnection();

        // Here we will run this query. If an exception occurs we'll determine if it was
        // caused by a connection that has been lost. If that is the cause, we'll try
        // to re-establish connection and re-run the query with a fresh connection.
        try {
            $result = $this->runQueryCallback($query, $bindings, $callback);
        } catch (Exception $e) {
            $result = $this->handleQueryException($e, $query, $bindings, $callback);
        }

        return $result;
    }

    protected function reconnectIfMissingConnection()
    {
        if (is_null($this->pdo)) {
            $this->reconnect();
        }
    }

    public function reconnect()
    {
        if (is_callable($this->reconnector)) {
            return call_user_func($this->reconnector, $this);
        }

        throw new Exception('Lost connection and no reconnector available.');
    }

    protected function runQueryCallback($query, $bindings, Closure $callback)
    {
        try {
            $result = $callback($query, $bindings);
        } catch (Exception $e) {
            throw $e;
        }

        return $result;
    }

    protected function handleQueryException(PDOException $e, $query, $bindings, Closure $callback)
    {
        throw $e;
    }

    /**
     * Set the reconnect instance on the connection.
     *
     * @param  callable  $reconnector
     * @return $this
     */
    public function setReconnector(callable $reconnector)
    {
        $this->reconnector = $reconnector;

        return $this;
    }

    public function disconnect()
    {
        $this->setPdo(null);
    }

    public function setPdo($pdo)
    {
        $this->transactions = 0;

        $this->pdo = $pdo;

        return $this;
    }

    /**
     * Get the current PDO connection.
     *
     * @return \PDO
     */
    public function getPdo()
    {
        $pdo = $this->pdo;
        if ($this->is_pool) {
            $pdo = $this->pdo['db'];
        }
        if ($pdo instanceof Closure) {
            return $pdo = call_user_func($pdo);
        }

        return $pdo;
    }

    /**
     * Run an insert statement against the database.
     *
     * @param  string  $query
     * @param  array   $bindings
     * @return bool
     */
    public function insert($query, $bindings = [])
    {
        return $this->statement($query, $bindings);
    }

    /**
     * Run an update statement against the database.
     *
     * @param  string  $query
     * @param  array   $bindings
     * @return int
     */
    public function update($query, $bindings = [])
    {
        return $this->affectingStatement($query, $bindings);
    }

    /**
     * Run a delete statement against the database.
     *
     * @param  string  $query
     * @param  array   $bindings
     * @return int
     */
    public function delete($query, $bindings = [])
    {
        return $this->affectingStatement($query, $bindings);
    }

    /**
     * Run a select statement against the database.
     *
     * @param  string  $query
     * @param  array  $bindings
     * @return array
     */
    public function select($query, $bindings = [])
    {
        return $this->run($query, $bindings, function ($query, $bindings) {
            $statement = $this->prepared($this->getPdo()->prepare($query));
            $this->bindValues($statement, $bindings);

            return $this->excute($this->beforeExcute($statement, 'fetchAll', $bindings));
        });
    }

    /**
     * Execute an SQL statement and return the boolean result.
     *
     * @param  string  $query
     * @param  array   $bindings
     * @return bool
     */
    public function statement($query, $bindings = [])
    {
        return $this->run($query, $bindings, function ($query, $bindings) {
            $statement = $this->getPdo()->prepare($query);

            $this->bindValues($statement, $bindings);

            $this->recordsHaveBeenModified();

            return $this->excute($this->beforeExcute($statement, '', $bindings));
        });
    }

    /**
     * Run an SQL statement and get the number of rows affected.
     *
     * @param  string  $query
     * @param  array   $bindings
     * @return int
     */
    public function affectingStatement($query, $bindings = [])
    {
        return $this->run($query, $bindings, function ($query, $bindings) {
            $statement = $this->getPdo()->prepare($query);
            $this->bindValues($statement, $bindings);

            $count = $this->excute($this->beforeExcute($statement, 'count', $bindings));

            $this->recordsHaveBeenModified(
                $count > 0
            );

            return $count;
        });
    }

    /**
     * Configure the PDO prepared statement.
     *
     * @param  \PDOStatement | CoStatement  $statement
     * @return \PDOStatement | CoStatement
     */
    protected function prepared($statement)
    {
        if ($statement instanceof PDOStatement) {
            $statement->setFetchMode($this->fetchMode);
        }

        return $statement;
    }

    /**
     * Bind values to their parameters in the given statement.
     *
     * @param  \PDOStatement | CoStatement $statement
     * @param  array  $bindings
     * @return void
     */
    public function bindValues($statement, $bindings)
    {
        $bindings = $this->prepareBindings($bindings);
        if ($statement instanceof PDOStatement) {
            foreach ($bindings as $key => $value) {
                $statement->bindValue(
                    is_string($key) ? $key : $key + 1,
                    $value,
                    is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR
                );
            }
        }
    }

    /**
     * Prepare the query bindings for execution.
     *
     * @param  array  $bindings
     * @return array
     */
    public function prepareBindings(array $bindings)
    {
        $grammar = $this->getQueryGrammar();

        foreach ($bindings as $key => $value) {
                // Transform all instances of DateTimeInterface into the actual date string
            if ($value instanceof DateTimeInterface) {
                $bindings[$key] = $value->format($grammar->getDateFormat());
            } elseif (is_bool($value)) {
                $bindings[$key] = (int)$value;
            }
        }

        return $bindings;
    }

    /**
     * Indicate if any records have been modified.
     *
     * @param  bool  $value
     * @return void
     */
    public function recordsHaveBeenModified($value = true)
    {
        if (!$this->recordsModified) {
            $this->recordsModified = $value;
        }
    }

    public function getName()
    {
        return isset($this->config['name']) ? $this->config['name'] : '';
    }

    protected function excute(Closure $closure)
    {
        return $this->is_pool ? DBContext::get('closure') : $closure();
    }

    /**
     * If is pool, set statument closure into context
     * @param PDOStatement | CoStatement $statement
     * @param string $mode [fetchAll, count, ...]
     * @param array $bindings
     */
    protected function beforeExcute($statement, string $mode = '', $bindings = [])
    {
        if ($statement instanceof PDOStatement) {
            $closure = function () use ($statement, $mode) {
                $result = $statement->execute();
                switch ($mode) {
                    case 'fetchAll':
                        $result = $statement->fetchAll();
                        break;
                    case 'count':
                        $result = $statement->fetchAll();
                        break;
                    default:
                        break;
                }
                if ($this->is_pool) {
                    App::get('db_pool')->push($this->pdo);
                }

                return $result;
            };
        }
        if ($statement instanceof CoStatement) {
            $bindings = $this->prepareBindings($bindings);
            $closure = function () use ($statement, $mode, $bindings) {
                $result = $statement->execute($bindings);
                switch ($mode) {
                    case 'fetchAll':
                        $result = $statement->fetchAll();
                        break;
                    case 'count':
                        $result = count($statement->fetchAll());
                        break;
                    default:
                        break;
                }
                App::get('db_pool')->push($this->pdo);

                return $result;
            };
        }
        if ($this->is_pool) {
            DBContext::set('closure', $closure);
        }

        return $closure;
    }
}
