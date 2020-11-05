<?php

return get('categories', new Mapper([
    '_id'        => 'empty',
    'plan_ids'   => 'empty',
    'post_id'    => 'empty',
    'post_ids'   => 'empty',
    'parent_id'  => 'id.categories',
    'updated_at' => 'datetime',
    'created_at' => 'datetime',
]));