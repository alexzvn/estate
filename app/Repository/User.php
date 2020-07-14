<?php

namespace App\Repository;

use App\Models\User as ModelsUser;

class User extends BaseRepository
{
    public function __construct(ModelsUser $model) {
        $this->model = $model;
    }
}
