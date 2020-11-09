<?php

use App\Models\User;

return array_reduce(get('permissions'), function ($carry, $perm)
{
    foreach ($perm['user_ids'] ?? [] as $oid) {

        if (empty($oid) || id('users', $oid) === null) continue;

        array_push($carry, [
            'permission_id' => id('permissions', $perm['_id']['$oid']),
            'model_type'    => User::class,
            'model_id'      => id('users', $oid)
        ]);
    }

    return $carry;
}, []);