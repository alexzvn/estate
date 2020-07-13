<?php

namespace App\Repository;

use App\Models\PostMeta;

class Meta extends BaseRepository
{
    public function __construct(PostMeta $model) {
        $this->model = $model;
    }
}
