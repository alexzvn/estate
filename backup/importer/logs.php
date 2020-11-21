<?php

return get('logs', new Mapper([
    'content'    => 'string',
    'link'       => 'string',
    'user_id'    => 'id.users',
    'updated_at' => 'datetime',
    'created_at' => 'datetime',
]));