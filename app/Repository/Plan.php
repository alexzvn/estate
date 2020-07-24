<?php

namespace App\Repository;

use App\Models\Plan as Model;

class Plan extends BaseRepository
{
    public function __construct(Model $model) {
        $this->setModel($model);
    }
}
