<?php

return get('orders', new Mapper([
    'status'               => 'int',
    'month'                => 'int',
    'verified'             => 'boolean',
    'discount'             => fn($val) => (int) $val,
    'discount_type'        => fn($val) => (int) $val,
    'origin_price'         => fn($val, $data) => ($data['price'] ?? 0),
    'price'                => fn($val, $data) => ($data['after_discount_price'] ?? 0),

    'customer_id'          => 'id.users',
    'creator_id'           => 'id.users',
    'verifier_id'          => 'id.users',

    'activate_at'          => 'datetime',
    'expires_at'           => 'datetime',
    'updated_at'           => 'datetime',
    'created_at'           => 'datetime',
    'deleted_at'           => 'datetime',
]));