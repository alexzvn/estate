<?php

return get('districts', new Mapper([
    'name'        => 'string',
    'type'        => 'string',
    'province_id' => 'id.provinces',
    'updated_at'  => 'datetime',
    'created_at'  => 'datetime',
]));