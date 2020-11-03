<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Maklad\Permission\Models\Role as Model;

class Role extends Model
{
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
