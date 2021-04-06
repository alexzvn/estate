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
        $permission = function ($builder) use ($perm) {
            $builder->whereIn('name', [$perm, '*']);
        };

        $user = User::whereHas('permissions', $permission)
            ->orWhereHas('roles', function ($builder) use ($permission) {
                $builder->whereHas('permissions', $permission);
            });

        return $user->get();
    }
}
