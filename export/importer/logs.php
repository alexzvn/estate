<?php

return get('logs', new Mapper([
    '_id' => 'empty',
    'user_id' => 'id.users',
    'updated_at' => 'datetime',
    'created_at' => 'datetime',
]));