<?php

return get('tracking_posts', new Mapper([
    'phone'             => 'string',
    'categories_unique' => 'int',
    'district_unique'   => 'int',
    'seen'              => 'int',
    'updated_at'        => 'datetime',
    'created_at'        => 'datetime',
]));