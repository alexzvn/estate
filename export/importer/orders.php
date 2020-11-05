<?php

return get('orders', new Mapper([
    'status'               => 'int',
    'month'                => 'int',
    'verified'             => 'boolean',
    'discount'             => 'int',
    'discount_type'        => 'int',
    'price'                => 'int',
    'after_discount_price' => 'int',

    'customer_id'          => 'id.users',
    'creator_id'           => 'id.users',
    'verifier_id'          => 'id.users',

    'activate_at'          => 'datetime',
    'expires_at'           => 'datetime',
    'updated_at'           => 'datetime',
    'created_at'           => 'datetime',
    'deleted_at'           => 'datetime',
], [
    'price' => 'origin_price',
    'after_discount_price' => 'price'
]));