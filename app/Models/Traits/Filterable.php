<?php

namespace App\Models\Traits;

use App\Filters\BaseFilter;

trait Filterable {
    /**
     * Scope a query to apply given filter.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param BaseFilter $filter
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter($query, BaseFilter $filter)
    {
        return $filter->apply($query);
    }
}