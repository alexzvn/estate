<?php

namespace App\Repository;

use App\Models\Post as ModelsPost;

class Post extends BaseRepository
{
    public function __construct(ModelsPost $model) {
        $this->model = $model;
    }
}
