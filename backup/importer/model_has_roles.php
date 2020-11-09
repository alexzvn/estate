<?php

use App\Models\User;

return array_reduce(get('roles'), function ($carry, $role)
{
    foreach ($role['user_ids'] ?? [] as $oid) {
        array_push([
            'role_id'    => id('roles', $role['_id']['$oid']),
            'model_type' => User::class,
            'model_id'   => id('users', $oid)
        ]);
    }

    return $carry;
}, []);