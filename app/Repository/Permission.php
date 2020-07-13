<?php

namespace App\Repository;

use App\Models\Permission as ModelsPermission;

class Permission extends BaseRepository
{
    public function __construct(ModelsPermission $model) {
        $this->model = $model;
    }
}
