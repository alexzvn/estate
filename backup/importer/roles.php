<?php

return get('roles', new Mapper([
    'name' => 'string',
    'guard_name' => 'string',
    'customer' => 'boolean',
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
]));