<?php

namespace App\Repository;

use App\Models\Post as ModelsPost;
use Closure;

class Post extends BaseRepository
{
    public function __construct(ModelsPost $model, Closure $beforeInject = null) {

        if ($beforeInject) {
            $this->model = $beforeInject($model);
        } else {
            $this->model = $model->with(['metas', 'metas.province', 'metas.district', 'categories']);
        }
    }
}
