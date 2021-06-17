<?php

return get('blacklists', new Mapper([
    'phone' => 'string',
    'user_id' => 'id.users',
    'name' => 'string',
    'url' => 'string',
    'category' => 'string',
    'updated_at' => 'datetime',
    'created_at' => 'datetime',
]));
