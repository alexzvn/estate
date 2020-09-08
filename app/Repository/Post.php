<?php

namespace App\Repository;

use App\Models\Post as ModelsPost;
use Closure;

class Post extends BaseRepository
{
    public function __construct(ModelsPost $model) {

        $this->model = $model;
    }

    public static function withRelation($relation = null)
    {
        $self = app()->make(get_called_class());

        return $self->with($relation ?? [
            'province',
            'district',
            'categories'
        ]);
    }
}
