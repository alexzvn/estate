<?php

return get('categories', new Mapper([
    'parent_id'  => 'id.categories',
    'updated_at' => 'datetime',
    'created_at' => 'datetime',
]));