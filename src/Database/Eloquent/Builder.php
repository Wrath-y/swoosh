<?php

namespace Src\Database\Eloquent;

use Closure;
use Exception;
use Src\Database\Eloquent\Relations\Relation;
use Src\Database\Query\Builder as QueryBuilder;

class Builder
{
    /**
     * The base query builder instance.
     *
     * @var \Src\Database\Query\Builder
     */
    protected $query;

    /**
     * The relationships that should be eager loaded.
     *
     * @var array
     */
    protected $eagerLoad = [];

    public function __construct(QueryBuilder $query)
    {
        $this->query = $query;
    }

    public function with($relations)
    {
        $eagerLoad = $this->parseWithRelations(is_string($relations) ? func_get_args() : $relations);

        $this->eagerLoad = array_merge($this->eagerLoad, $eagerLoad);

        return $this;
    }

    protected function parseWithRelations(array $relations)
    {
        if (!$relations) {
            return [];
        }
        $results = [];

        foreach ($relations as $name => $constraints) {
            if (is_numeric($name)) {
                $name = $constraints;
                list($name, $constraints) = strpos($name, ':')
                    ? $this->createSelectWithConstraint($name)
                    : [$name, function () {
                        //
                    }];
            }

            $results = $this->addNestedWiths($name, $results);

            $results[$name] = $constraints;
        }

        return $results;
    }

    protected function createSelectWithConstraint($name)
    {
        return [explode(':', $name)[0], function ($query) use ($name) {
            $query->select(explode(',', explode(':', $name)[1]));
        }];
    }

    protected function addNestedWiths($name, $results)
    {
        $progress = [];

        foreach (explode('.', $name) as $segment) {
            $progress[] = $segment;

            if (!isset($results[$last = implode('.', $progress)])) {
                $results[$last] = function () {
                    //
                };
            }
        }

        return $results;
    }

    /**
     * Execute the query as a "select" statement.
     *
     * @param  array  $columns
     * @return array|static[]
     */
    public function get($columns = ['*'])
    {
        if (count($models = $this->getModels($columns)) > 0) {
            $models = $this->eagerLoadRelations($models);
        }

        return $models;
    }

    /**
     * Set a model instance for the model being queried.
     *
     * @param  \Src\Database\Eloquent\Model  $model
     * @return $this
     */
    public function setModel(Model $model)
    {
        $this->model = $model;

        $this->query->from($model->getTable());

        return $this;
    }

    /**
     * Get the underlying query builder instance.
     *
     * @return \Src\Database\Query\Builder
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Get the model instance being queried.
     *
     * @return \Src\Database\Eloquent\Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
 * Get the hydrated models without eager loading.
 *
 * @param  array  $columns
 * @return \Src\Database\Eloquent\Model[]
 */

    public function getModels($columns = ['*'])
    {
        return $this->model->hydrate(
            (array) $this->query->get($columns)
        );
    }

    public function hydrate(array $items)
    {
        $instance = $this->newModelInstance();

        return array_map(function ($item) use ($instance) {
            return $instance->newFromBuilder($item);
        }, $items);
    }

    public function newModelInstance($attributes = [])
    {
        return $this->model->newInstance($attributes)->setConnection(
            $this->query->getConnection()->getName()
        );
    }

    /**
     * Eager load the relationships for the models.
     *
     * @param  array  $models
     * @return array
     */
    public function eagerLoadRelations(array $models)
    {
        foreach ($this->eagerLoad as $name => $constraints) {
            if (strpos($name, '.') === false) {
                $models = $this->eagerLoadRelation($models, $name, $constraints);
            }
        }

        return $models;
    }

    /**
     * Eagerly load the relationship on a set of models.
     *
     * @param  array  $models
     * @param  string  $name
     * @param  \Closure  $constraints
     * @return array
     */
    protected function eagerLoadRelation(array $models, $name, Closure $constraints)
    {
        $relation = $this->getRelation($name);

        $relation->addEagerConstraints($models);

        $constraints($relation);

        return $relation->match(
            $relation->initRelation($models, $name),
            $relation->getEager(),
            $name
        );
    }

    /**
     * Get the relation instance for the given relation name.
     *
     * @param  string  $name
     * @return \Src\Database\Eloquent\Relations\Relation
     */
    public function getRelation($name)
    {
        $relation = Relation::noConstraints(function () use ($name) {
            try {
                return $this->getModel()->newInstance()->$name();
            } catch (Exception $e) {
                throw $e->getMessage();
            }
        });

        $nested = $this->relationsNestedUnder($name);

        if (count($nested) > 0) {
            $relation->getQuery()->with($nested);
        }

        return $relation;
    }

    /**
     * Get the deeply nested relations for a given top-level relation.
     *
     * @param  string  $relation
     * @return array
     */
    protected function relationsNestedUnder($relation)
    {
        $nested = [];

        // We are basically looking for any relationships that are nested deeper than
        // the given top-level relationship. We will just check for any relations
        // that start with the given top relations and adds them to our arrays.
        foreach ($this->eagerLoad as $name => $constraints) {
            if ($this->isNestedUnder($relation, $name)) {
                $nested[substr($name, strlen($relation . '.'))] = $constraints;
            }
        }

        return $nested;
    }

    /**
     * Determine if the relationship is nested.
     *
     * @param  string  $relation
     * @param  string  $name
     * @return bool
     */
    protected function isNestedUnder($relation, $name)
    {
        return strpos($name, '.') && startsWith($name, $relation . '.');
    }

    public function where($column, $operator = null, $value = null, $boolean = 'and')
    {
        if ($column instanceof Closure) {
            $column($query = $this->model->newModelQuery());

            $this->query->addNestedWhereQuery($query->getQuery(), $boolean);
        } else {
            $this->query->where(...func_get_args());
        }

        return $this;
    }

    public function __call($method, $parameters)
    {
        $this->query->{$method}(...$parameters);

        return $this;
    }
}
