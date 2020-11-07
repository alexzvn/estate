<?php

return get('wards', new Mapper([
    'name'        => 'string',
    'type'        => 'string',
    'district_id' => 'id.districts',
    'updated_at'  => 'datetime',
    'created_at'  => 'datetime'
]));