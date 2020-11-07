<?php

return get('categories', new Mapper([
    'parent_id'   => 'id.categories',
    'name'        => 'string',
    'description' => 'string',
    'updated_at'  => 'datetime',
    'created_at'  => 'datetime',
]));