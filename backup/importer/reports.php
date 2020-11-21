<?php

return get('reports', new Mapper([
    'post_id' => 'id.posts',
    'user_id' => 'id.users',
    'updated_at' => 'datetime',
    'created_at' => 'datetime'
]));