<?php

use App\Enums\PostStatus;

$type = function ($type) {
    $mapped = [
        'Tin Xin Phí' => 1,
        'Tin Mua Bán - Thuê' => 2,
        'Tin Thị Trường' => 3,
        'Tin web online' => 4
    ];

    return $mapped[$type] ?? null;
};

$maxInt = pow(2, 64) - 1;

$price = function ($price) use ($maxInt) {

    if (! $price) {
        return null;
    }

    $price = ($price['$numberLong'] ?? $price);

    return $price < 1 || $price >= $maxInt ? null : $price;
};


return get('posts', new Mapper([
    'title' => 'string',
    'content' => 'string',
    'phone' => 'string',
    'hash' => 'string',
    'type' => $type,
    'reverser' => 'boolean',
    'approve_fee' => 'boolean',
    'commission' => 'string',
    'price' => $price,
    'status' => fn($status = null) => $status ?? PostStatus::Draft,
    'extra' => fn($extra) => $extra ? json_encode($extra) : null,
    'source' => 'int',
    'verifier_id' => 'id.users',
    'user_id' => 'id.users',
    'province_id' => 'id.provinces',
    'district_id' => 'id.districts',
    'publish_at' => 'datetime',
    'updated_at' => 'datetime',
    'created_at' => 'datetime',
    'deleted_at' => 'datetime',
], [
    'approveFee' => 'approve_fee'
]));
