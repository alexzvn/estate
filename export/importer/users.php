<?php

return get('users', new Mapper([
    'updated_at'         => 'datetime',
    'created_at'         => 'datetime',
    'last_seen'          => 'datetime',
    'phone_verified_at'  => 'datetime',
    'birthday'           => 'datetime',
    'banned_at'          => 'datetime',
    'role_ids'           => 'empty',
    '_id'                => 'empty',
    'post_save_ids'      => 'empty',
    'permission_ids'     => 'empty',
    'post_blacklist_ids' => 'empty',
    'supporter_id'       => 'id.users',
]));