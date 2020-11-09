<?php

return get('permissions', new Mapper([
    'name' => 'string',
    'display_name' => 'string',
    'guard_name' => 'string',
    'group_id' => 'id.permission_groups',
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
]));