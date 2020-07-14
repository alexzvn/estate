<?php

namespace App\Models\Traits;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Jenssegers\Mongodb\Eloquent\Builder;

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
    protected $filterable = [];

    public function scopeFilterRequest(Builder $query, Request $request)
    {
        foreach ($request->all() as $field => $value) {

            if ($value === '') continue;

            $method = 'filter' . Str::studly($value);

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


