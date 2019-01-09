<?php

namespace Src\Server\Database\Query\Grammars;

use Src\Server\Database\Query\Builder;
use Src\Server\Database\Query\Grammar;

class Grammar extends Grammar
{
    /**
     * The grammar specific operators.
     *
     * @var array
     */
    protected $operators = [];

    /**
     * The components that make up a select clause.
     *
     * @var array
     */
    protected $selectComponents = [
        'aggregate',
        'columns',
        'from',
        'joins',
        'wheres',
        'groups',
        'havings',
        'orders',
        'limit',
        'offset',
        'unions',
        'lock',
    ];

    /**
     * Determine if the grammar supports savepoints.
     *
     * @return bool
     */
    public function supportsSavepoints()
    {
        return true;
    }

    /**
     * Compile the SQL statement to define a savepoint.
     *
     * @param  string  $name
     * @return string
     */
    public function compileSavepoint($name)
    {
        return 'SAVEPOINT ' . $name;
    }

    /**
     * Compile the SQL statement to execute a savepoint rollback.
     *
     * @param  string  $name
     * @return string
     */
    public function compileSavepointRollBack($name)
    {
        return 'ROLLBACK TO SAVEPOINT ' . $name;
    }

    /**
     * Compile a select query into SQL.
     *
     * @param  \Src\Server\Database\Query\Builder  $query
     * @return string
     */
    public function compileSelect(Builder $query)
    {
        $original = $query->columns;

        if (is_null($query->columns)) {
            $query->columns = ['*'];
        }

        $sql = trim($this->concatenate(
            $this->compileComponents($query)
        ));

        $query->columns = $original;

        return $sql;
    }

    /**
     * Concatenate an array of segments, removing empties.
     *
     * @param  array   $segments
     * @return string
     */
    protected function concatenate($segments)
    {
        return implode(' ', array_filter($segments, function ($value) {
            return (string)$value !== '';
        }));
    }

    /**
     * Compile the components necessary for a select clause.
     *
     * @param  \Src\Server\Database\Query\Builder  $query
     * @return array
     */
    protected function compileComponents(Builder $query)
    {
        $sql = [];

        foreach ($this->selectComponents as $component) {
            if (!is_null($query->$component)) {
                $method = 'compile' . ucfirst($component);

                $sql[$component] = $this->$method($query, $query->$component);
            }
        }

        return $sql;
    }

    /**
     * Compile the "select *" portion of the query.
     *
     * @param  \Src\Server\Database\Query\Builder  $query
     * @param  array  $columns
     * @return string|null
     */
    protected function compileColumns(Builder $query, $columns)
    {
        if (!is_null($query->aggregate)) {
            return;
        }

        $select = $query->distinct ? 'select distinct ' : 'select ';

        return $select . $this->columnize($columns);
    }

    /**
     * Compile the "where" portions of the query.
     *
     * @param  \Src\Server\Database\Query\Builder  $query
     * @return string
     */
    protected function compileWheres(Builder $query)
    {
        if (is_null($query->wheres)) {
            return '';
        }
        if (count($sql = $this->compileWheresToArray($query)) > 0) {
            return $this->concatenateWhereClauses($query, $sql);
        }

        return '';
    }

    /**
     * Format the where clause statements into one string.
     *
     * @param  \Src\Server\Database\Query\Builder  $query
     * @param  array  $sql
     * @return string
     */
    protected function concatenateWhereClauses($query, $sql)
    {
        $conjunction = 'where';

        return $conjunction . ' ' . $this->removeLeadingBoolean(implode(' ', $sql));
    }

    /**
     * Remove the leading boolean from a statement.
     *
     * @param  string  $value
     * @return string
     */
    protected function removeLeadingBoolean($value)
    {
        return preg_replace('/and |or /i', '', $value, 1);
    }

    /**
     * Compile a basic where clause.
     *
     * @param  \Src\Server\Database\Query\Builder  $query
     * @param  array  $where
     * @return string
     */
    protected function whereBasic(Builder $query, $where)
    {
        $value = $this->parameter($where['value']);

        return $this->wrap($where['column']) . ' ' . $where['operator'] . ' ' . $value;
    }

    /**
     * Get the grammar specific operators.
     *
     * @return array
     */
    public function getOperators()
    {
        return $this->operators;
    }
}
