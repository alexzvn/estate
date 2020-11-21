<?php

namespace App\Models;

use App\Models\Traits\CacheDefault;
use Illuminate\Database\Eloquent\Model;

class PermissionGroup extends Model
{
    use CacheDefault;

    protected $fillable = ['name'];

    public function permissions()
    {
        return $this->hasMany(Permission::class, 'group_id');
    }
}
