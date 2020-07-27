<?php

namespace App\Repository;

use Illuminate\Support\Collection;
use App\Models\Category as ModelsCategory;

class Category extends BaseRepository
{
    public function __construct(ModelsCategory $model) {
        $this->model = $model;
    }

    /**
     * Flatten category parent & childen
     *
     * @param Collection $categories
     * @return \App\Models\Category[]|Illuminate\Support\Collection
     */
    public static function flat(Collection $categories)
    {
        return $categories->reduce(function (Collection $carry, $item) {
            $carry->push($item);

            if ($item->children) {
                return $carry->push(...$item->children);
            }

            return $carry;
        }, collect());
    }
}
