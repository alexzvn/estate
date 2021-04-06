<?php

return get('users', new Mapper([
    'name'              => 'string',
    'email'             => 'string',
    'phone'             => 'string',
    'password'          => 'string',
    'address'           => 'string',
    'session_id'        => 'string',
    'remember_token'    => 'string',
    'supporter_id'      => 'id.users',
    'updated_at'        => 'datetime',
    'created_at'        => 'datetime',
    'deleted_at'        => 'datetime',
    'last_seen'         => 'datetime',
    'phone_verified_at' => 'datetime',
    'email_verified_at' => 'datetime',
    'birthday'          => 'datetime',
    'banned_at'         => 'datetime',
]));
