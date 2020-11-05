<?php

return get('posts', new Mapper([
    '_id' => 'empty',
    'category_ids'=> 'empty',
    'user_save_ids'=> 'empty',
    'user_blacklist_ids'=> 'empty',
    'file_ids'=> 'empty',
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