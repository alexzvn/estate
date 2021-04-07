<?php

namespace App\Models\ScoutFilter;

use Laravel\Scout\Builder;

class OrderFilter extends Filter
{
    public function filterPlan(Builder $builder, $value)
    {
        $builder->whereIn('plan_id', [$value]);
    }
}
