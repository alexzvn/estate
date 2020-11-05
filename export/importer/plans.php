<?php

return get('plans', new Mapper([
    'name' => 'string',
    'price' => 'int',
    'updated_at' => 'datetime',
    'created_at' => 'datetime',
]));