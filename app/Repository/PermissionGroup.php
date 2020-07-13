<?php

namespace App\Repository;

use App\Models\PermissionGroup as ModelsPermissionGroup;

class PermissionGroup extends BaseRepository
{
    public function __construct(ModelsPermissionGroup $model) {
        $this->model = $model;
    }
}
