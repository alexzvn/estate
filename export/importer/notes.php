<?php

use App\Models\Order;
use App\Models\User;

$noteType = function ($value, array $data)
{
    if (isset($data['user_id'])) {
        return User::class;
    }

    return Order::class;
};

$noteId = function ($value, array $data) use ($noteType)
{
    if ($noteType($value, $data) === User::class) {
        return id('users', $value);
    }

    return id('orders', $value);
};

return get('notes', new Mapper([
    'content'      => 'string',
    'notable_id'   => $noteId,
    'notable_type' => $noteType,
    'updated_at'   => 'datetime',
    'created_at'   => 'datetime',
]));