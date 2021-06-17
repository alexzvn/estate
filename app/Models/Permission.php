<?php

namespace App\Models;

use App\Models\Traits\CacheDefault;
use Spatie\Permission\Models\Permission as Model;

class Permission extends Model
{
    use CacheDefault;

    public function group()
    {
        return $this->belongsTo(PermissionGroup::class, 'group_id');
    }
}
