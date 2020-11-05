<?php

return get('blacklists', new Mapper([
    'phone' => 'string',
    'user_id' => 'id.users',
    'updated_at' => 'datetime',
    'created_at' => 'datetime',
]));