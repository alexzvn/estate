<?php

namespace App\Models;

use App\Models\Traits\CacheDefault;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Models\Role as Model;

class Role extends Model
{
    use CacheDefault;

    public function markAsForCustomer()
    {
        return $this->forceFill([
            'customer' => true
        ])->save();
    }

    public function markNotForCustomer()
    {
        return $this->forceFill([
            'customer' => false
        ])->save();
    }

    public function scopeCustomer(Builder $builder)
    {
        return $builder->where('customer', true);
    }

    public function scopeStaff(Builder $builder)
    {
        return $builder->where('customer', '<>', true);
    }
}
