<?php

return get('files', new Mapper([
    'name' => 'string',
    'path' => 'string',
    'post_id' => fn($v, $data) => isset($data['post_ids'][0]) ? id('posts', $data['post_ids'][0]) : null,
    'updated_at' => 'datetime',
    'created_at' => 'datetime',
]));
