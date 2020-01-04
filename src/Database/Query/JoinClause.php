<?php

namespace Src\Database\Query;

use Closure;

class JoinClause extends Builder
{
    /**
     * The type of join being performed.
     *
     * @var string
     */
    public $type;

    /**
     * The table the join clause is joining to.
     *
     * @var string
     */
    public $table;

    /**
     * The parent query builder instance.
     *
     * @var \Src\Database\Query\Builder
     */
    private $builder;

    /**
     * Create a new join clause instance.
     *
     * @param  \Src\Database\Query\Builder $builder
     * @param  string  $type
     * @param  string  $table
     * @return void
     */
    public function __construct(Builder $builder, $type, $table)
    {
        $this->type = $type;
        $this->table = $table;
        $this->builder = $builder;

        parent::__construct(
            $builder->getConnection(),
            $builder->getGrammar(),
            $builder->getProcessor()
        );
    }

    /**
     * Add an "on" clause to the join.
     *
     * On clauses can be chained, e.g.
     *
     *  $join->on('contacts.user_id', '=', 'users.id')
     *       ->on('contacts.info_id', '=', 'info.id')
     *
     * will produce the following SQL:
     *
     * on `contacts`.`user_id` = `users`.`id`  and `contacts`.`info_id` = `info`.`id`
     *
     * @param  string  $first
     * @param  string|null  $operator
     * @param  string|null  $second
     * @param  string  $boolean
     * @return $this
     */
    public function on($first, $operator = null, $second = null, $boolean = 'and')
    {
        return $this->whereColumn($first, $operator, $second, $boolean);
    }
}
