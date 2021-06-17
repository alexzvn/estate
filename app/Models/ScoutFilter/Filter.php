<?php

namespace App\Models\ScoutFilter;

use Illuminate\Support\Str;
use Illuminate\Http\Request;

abstract class Filter
{
    public static function filter($query, $filters)
    {
        $filters = $filters instanceof Request ? $filters->all() : $filters;

        $instance = new static();

        foreach ($filters as $field => $value) {

            if ($value === '' || is_null($value)) continue;

            $method = 'filter' . Str::studly($field);

            if (method_exists($instance, $method)) {
                $instance->$method($query, $value);
            }

            if ( empty($instance->filterable) ) continue;

            if (in_array($field, $instance->filterable)) {
                $query->where($field, $value);
                continue;
            }

            if (key_exists($field, $instance->filterable)) {
                $query->where($instance->filterable[$field], $value);
                continue;
            }
        }

        return $query;
    }
}
