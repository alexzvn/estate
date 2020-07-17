<?php

namespace App\Repository;

use App\Models\Role as ModelsRole;
use Illuminate\Support\Facades\Cache;

class Role extends BaseRepository
{
    public function __construct(ModelsRole $model) {
        $this->model = $model;
    }
}
