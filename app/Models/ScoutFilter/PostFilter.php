<?php

namespace App\Models\ScoutFilter;

use Illuminate\Support\Carbon;

class PostFilter extends Filter
{
    public function filterQuery($builder, $query)
    {
        if (! empty($query) && $query !== '*') {
            $builder->minScore(0.4);
        }
    }

    public function filterProvince($builder, $value)
    {
        $builder->where('province_id', (int) $value);
    }

    public function filterProvinces($builder, $value)
    {
        $builder->whereIn('province_id', (int) $value);
    }

    public function filterDistrict($builder, $value)
    {
        $builder->where('district_id', (int) $value);
    }

    public function filterCategories($builder, $values)
    {
        $values = is_string($values) ? [$values] : $values;

        $values = array_map(fn($v) => (int) $v, $values);

        $builder->whereIn('category_id', $values);
    }

    public function filterStatus($builder, $status)
    {
        $builder->where('status', (int) $status);
    }

    public function filterFrom($builder, $date)
    {
        if ($date = strtotime($date)) {
            $builder->where('publish_at', '>=', Carbon::createFromTimestamp($date)->startOfDay());
        }
    }

    public function filterTo($builder, $date)
    {
        if ($date = strtotime($date)) {
            $builder->where('publish_at', '<=', Carbon::createFromTimestamp($date)->endOfDay());
        }
    }

    public function filterPrice($builder, $price)
    {
        if (! is_string($price)) {
            return;
        }

        @[$min, $max] = explode('-', $price);

        $min && $this->filterMinPrice($builder, (int) $min);
        $max && $this->filterMaxPrice($builder, (int) $max);
    }

    public function filterMinPrice($builder, int $price)
    {
        $builder->where('price', '>=', $price);
    }

    public function filterMaxPrice($builder, int $price)
    {
        $builder->where('price', '<=', $price);
    }

    public function filterOrder($builder, $type)
    {
        switch ($type) {
            case 'accurate':  return $builder->orderBy('_score', 'desc');
            case 'newest':    return $builder->orderBy('publish_at', 'desc');
        }
    }
}
