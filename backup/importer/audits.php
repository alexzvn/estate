<?php

use Illuminate\Support\Str;

$auditable = function ($value, $data)
{
    $table = explode('\\', $data['auditable_type']);
    $table = Str::plural(Str::snake($table[count($table) -1]));

    return id($table, $data['_id']);
};

return get('audits', new Mapper([
    'user_id'        => 'id.users',
    'user_type'      => 'string',
    'event'          => 'string',
    'auditable_id'   => $auditable,
    'auditable_type' => 'string',
    'old_values'     => 'string',
    'new_values'     => 'string',
    'url'            => 'string',
    'ip_address'     => 'string',
    'user_agent'     => 'string',
    'tags'           => 'string',
    'updated_at'     => 'datetime',
    'created_at'     => 'datetime',
]));
