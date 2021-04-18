<?php

$saveSupport = function ($value, $data) {
    if (! isset($value) || ! ($id = id('users', $value))) {
        return null;
    }

    if (! $uid = id('users', $data['_id'])) {
        return null;
    }

    file_put_contents(backup_path('mapper.ids'),"$uid|$id\n", FILE_APPEND);
};

return get('users', new Mapper([
    'name'              => 'string',
    'email'             => 'string',
    'phone'             => 'string',
    'password'          => 'string',
    'address'           => 'string',
    'session_id'        => 'string',
    'remember_token'    => 'string',
    'supporter_id'      => $saveSupport,
    'updated_at'        => 'datetime',
    'created_at'        => 'datetime',
    'deleted_at'        => 'datetime',
    'last_seen'         => 'datetime',
    'phone_verified_at' => 'datetime',
    'email_verified_at' => 'datetime',
    'birthday'          => 'null',
    'banned_at'         => 'datetime',
]));
