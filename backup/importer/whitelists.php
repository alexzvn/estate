<?php

return get('whitelists', new Mapper([
    'phone' => 'string',
    'user' => 'id.users',
    'updated_at' => 'datetime',
    'created_at' => 'datetime',
]));