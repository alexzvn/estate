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
        $findPerm = function ($builder) use ($perm) {
            $builder->orWhereIn('name', [$perm, '*']);
        };

        $user = User::orWhereHas('permissions', $findPerm)
            ->orWhereHas('roles', function ($builder) use ($findPerm)
            {
                $builder->orWhereHas('permissions', $findPerm);
            });

        return $user->get();
    }
}
