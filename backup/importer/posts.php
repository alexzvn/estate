<?php

$type = function ($oid, $data) {
    $mapped = [
        'Tin Xin Phí' => 1,
        'Tin Mua Bán - Thuê' => 2,
        'Tin Thị Trường' => 3,
        'Tin web online' => 4
    ];

    return $mapped[$data['type']] ?? null;
};

$price = function ($oid, $data) {
    $price = $data['price'];

    if (! $price) {
        return 0;
    }

    if ( !is_int($price) && $price !== null) {
        return (int) $price->{'$numberLong'};
    }

    return $price;
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
    'index_meta' => 'string',
    'price' => $price,
    'status' => 'int',
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
