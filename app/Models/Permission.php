<?php

namespace App\Models;

use Maklad\Permission\Models\Permission as Model;

class Permission extends Model
{
    public function group()
    {
        return $this->belongsTo(PermissionGroup::class);
    }
}
