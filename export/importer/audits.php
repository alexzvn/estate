<?php

use Illuminate\Support\Str;

$auditable = function ($oid, $data)
{
    $table = explode('\\', $data['auditable_type']);
    $table = Str::plural(Str::snake($table[count($table) -1]));

    return id($table, $oid);
};

return get('audits', new Mapper([
    '_id' => 'empty',
    'auditable_id' => $auditable,
    'user_id' => 'id.users',
    'updated_at' => 'datetime',
    'created_at' => 'datetime',
]));