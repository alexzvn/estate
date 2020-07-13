<?php

namespace App\Repository;

use App\Models\Category as ModelsCategory;

class Category extends BaseRepository
{
    public function __construct(ModelsCategory $model) {
        $this->model = $model;
    }
}
