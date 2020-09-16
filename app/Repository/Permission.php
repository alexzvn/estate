<?php

namespace App\Repository;

use App\Models\Permission as ModelsPermission;

class Permission extends BaseRepository
{
    public function __construct(ModelsPermission $model) {
        $this->model = $model;
    }

    /**
     * Get list users by permission
     *
     * @return \Illuminate\Database\Eloquent\Collection|App\Models\User[]|array
     */
    public static function findUsersHasPermission(string $perm)
    {
        $staffGroup = static::findByName($perm)->users;

        $adminGroup = static::findByName('*')->users;

        return $adminGroup->push(...$staffGroup)->unique('id');
    }
}
