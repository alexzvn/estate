<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class PermissionGroup extends Model
{
    protected $fillable = ['name'];

    public function permissions()
    {
        return $this->hasMany(Permission::class, 'group_id');
    }
}
