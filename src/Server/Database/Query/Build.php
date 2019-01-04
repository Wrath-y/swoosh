<?php

namespace Src\Server\Database\Query;

use Src\Server\Database\ConnectionInterface;

class Build
{
    /**
     * The database connection instance.
     *
     * @var \Server\Server\Database\ConnectionInterface
     */
    public $connection;

    /**
     * The database query grammar instance.
     *
     * @var \Server\Server\Database\Query\Grammars\Grammar
     */
    public $grammar;

    /**
     * The database query post processor instance.
     *
     * @var \Server\Server\Database\Query\Processors\Processor
     */
    public $processor;

    /**
     * The current query value bindings.√ß
     *
     * @var array
     */
    public $bindings = [
        'select' => [],
        'join' => [],
        'where' => [],
        'having' => [],
        'order' => [],
        'union' => [],
    ];

    /**
     * An aggregate function and column to be run.
     *
     * @var array
     */
    public $aggregate;

    /**
     * The columns that should be returned.
     *
     * @var array
     */
    public $columns;

    /**
     * Indicates if the query returns distinct results.
     *
     * @var bool
     */
    public $distinct = false;

    /**
     * The table which the query is targeting.
     *
     * @var string
     */
    public $from;

    /**
     * The table joins for the query.
     *
     * @var array
     */
    public $joins;

    /**
     * The where constraints for the query.
     *
     * @var array
     */
    public $wheres = [];

    /**
     * The groupings for the query.
     *
     * @var array
     */
    public $groups;

    /**
     * The having constraints for the query.
     *
     * @var array
     */
    public $havings;

    /**
     * The orderings for the query.
     *
     * @var array
     */
    public $orders;

    /**
     * The maximum number of records to return.
     *
     * @var int
     */
    public $limit;

    /**
     * The number of records to skip.
     *
     * @var int
     */
    public $offset;

    /**
     * The query union statements.
     *
     * @var array
     */
    public $unions;

    /**
     * The maximum number of union records to return.
     *
     * @var int
     */
    public $unionLimit;

    /**
     * The number of union records to skip.
     *
     * @var int
     */
    public $unionOffset;

    /**
     * The orderings for the union query.
     *
     * @var array
     */
    public $unionOrders;

    /**
     * Indicates whether row locking is being used.
     *
     * @var string|bool
     */
    public $lock;

    /**
     * All of the available clause operators.
     *
     * @var array
     */
    public $operators = [
        '=', '<', '>', '<=', '>=', '<>', '!=', '<=>',
        'like', 'like binary', 'not like', 'ilike',
        '&', '|', '^', '<<', '>>',
        'rlike', 'regexp', 'not regexp',
        '~', '~*', '!~', '!~*', 'similar to',
        'not similar to', 'not ilike', '~~*', '!~~*',
    ];

    /**
     * Create a new query builder instance.
     *
     * @param  Src\Server\Database\ConnectionInterface  $connection
     * @param  Src\Server\Database\Query\Grammars\Grammar  $grammar
     * @param  Src\Server\Database\Query\Processors\Processor  $processor
     * @return void
     */
    public function __construct( ConnectionInterface $connection, Grammar $grammar = null, Processor $processor = null) {
        $this->connection = $connection;
        $this->grammar = $grammar ? : $connection->getQueryGrammar();
        $this->processor = $processor ? : $connection->getPostProcessor();
    }
}
