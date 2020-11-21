<?php

use App\Models\Blacklist;
use App\Models\Order;
use App\Models\User;

$noteType = function ($value, array $data)
{
    switch (true) {
        case isset($data['user_id']): return User::class;
        case isset($data['order_id']): return Order::class;
        case isset($data['blacklist_id']): return Blacklist::class;
    }

    return dd($data);
};

$noteId = function ($value, array $data) use ($noteType)
{
    $type = $noteType($value, $data);

    switch ($type) {
        case User::class: return id('users', $data['user_id']);
        case Order::class: return id('orders', $data['order_id']);
        case Blacklist::class: return id('blacklists', $data['blacklist_id']);
    }

    return dd($data);
};

return get('notes', new Mapper([
    'content'      => 'string',
    'notable_id'   => $noteId,
    'notable_type' => $noteType,
    'updated_at'   => 'datetime',
    'created_at'   => 'datetime',
]));