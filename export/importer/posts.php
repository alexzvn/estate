<?php

return get('posts', new Mapper([
    'title' => 'string',
    'content' => 'string',
    'phone' => 'string',
    'hash' => 'string',
    'type' => 'string',
    'reverser' => 'boolean',
    'approve_fee' => 'boolean',
    'commission' => 'string',
    'index_meta' => 'string',
    'price' => 'int',
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