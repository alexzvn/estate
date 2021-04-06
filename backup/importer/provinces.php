<?php

return get('provinces', new Mapper([
    'name'       => 'string',
    'type'       => 'string',
    'active'     => 'boolean',
    'updated_at' => 'datetime',
    'created_at' => 'datetime',
]));
