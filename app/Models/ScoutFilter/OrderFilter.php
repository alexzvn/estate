<?php

namespace App\Models\ScoutFilter;

use Laravel\Scout\Builder;
use Illuminate\Support\Carbon;

class OrderFilter extends Filter
{
    public function filterPlan(Builder $builder, $value)
    {
        $builder->whereIn('plan', [$value]);
    }

    public function filterCreator(Builder $builder, $value)
    {
        $builder->where('creator.id', $value);
    }

    public function filterPriceGreater(Builder $builder, $value)
    {
        $builder->where('price', '>=', (int) $value);
    }

    public function filterActivatedFrom(Builder $builder, $time)
    {
        $time = Carbon::createFromFormat('d/m/Y', $time)->startOfDay();

        $builder->where('activate_at', '>=', $time->timestamp);
    }

    public function filterActivatedTo(Builder $builder, $time)
    {
        $time = Carbon::createFromFormat('d/m/Y', $time)->endOfDay();

        $builder->where('activate_at', '<=', $time->timestamp);
    }
}
