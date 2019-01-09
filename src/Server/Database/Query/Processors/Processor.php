<?php

namespace Src\Server\Database\Query\Processors;

use Src\Server\Database\Query\Builder;

class Processor
{
    /**
     * Process the results of a "select" query.
     *
     * @param  \Src\Server\Database\Query\Builder  $query
     * @param  array  $results
     * @return array
     */
    public function processSelect(Builder $query, $results)
    {
        return $results;
    }
}
