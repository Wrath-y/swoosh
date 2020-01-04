<?php

namespace Src\Database\Eloquent\Relations;

use Closure;
use Src\Database\Eloquent\Model;
use Src\Database\Eloquent\Builder;

abstract class Relation
{
    /**
     * Indicates if the relation is adding constraints.
     *
     * @var bool
     */
    protected static $constraints = true;

    /**
     * Create a new relation instance.
     *
     * @param  Src\Database\Eloquent\Builder  $query
     * @param  Src\Database\Eloquent\Model  $parent
     * @return void
     */
    public function __construct(Builder $query, Model $parent)
    {
        $this->query = $query;
        $this->parent = $parent;
        $this->related = $query->getModel();

        $this->addConstraints();
    }

    public static function noConstraints(Closure $callback)
    {
        $previous = static::$constraints;

        static::$constraints = false;

        try {
            return call_user_func($callback);
        }
        finally {
            static::$constraints = $previous;
        }
    }

    /**
     * Get the relationship for eager loading.
     *
     * @return array
     */
    public function getEager()
    {
        return $this->get();
    }

    /**
     * Execute the query as a "select" statement.
     *
     * @param  array  $columns
     * @return array
     */
    public function get($columns = ['*'])
    {
        return $this->query->get($columns);
    }

    /**
     * Set the base constraints on the relation query.
     *
     * @return void
     */
    abstract public function addConstraints();

    /**
     * Set the constraints for an eager load of the relation.
     *
     * @param  array  $models
     * @return void
     */
    abstract public function addEagerConstraints(array $models);

    /**
     * Match the eagerly loaded results to their parents.
     *
     * @param  array   $models
     * @param  $results
     * @param  string  $relation
     * @return array
     */
    abstract public function match(array $models, $results, $relation);

    /**
     * Get the results of the relationship.
     *
     * @return mixed
     */
    abstract public function getResults();
}
