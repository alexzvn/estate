<?php

namespace App\Models\Traits;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

/**
 * 
 */
trait CanFilter
{
    /**
     * The attributes that define filter field from request
     *
     * @var array
     */
    // protected $filterable = [];

    public function scopeFilter(Builder $query, $filters)
    {
        $filters = $filters instanceof Request ? $filters->all() : $filters;

        foreach ($filters as $field => $value) {

            if ($value === '' || is_null($value)) continue;

            $method = 'filter' . Str::studly($field);

            if (method_exists($this, $method)) {
                $this->$method($query, $value);
            }

            if ( empty($this->filterable) ) continue;

            if (in_array($field, $this->filterable)) {
                $query->where($field, $value);
                continue;
            }

            if (key_exists($field, $this->filterable)) {
                $query->where($this->filterable[$field], $value);
                continue;
            }
        }

        return $query;
    }
}


