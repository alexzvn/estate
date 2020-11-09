<?php

namespace App\Models;

use App\Models\Traits\CacheDefault;
use Illuminate\Database\Eloquent\Model;

class PlanType extends Model
{
    use CacheDefault;

    protected $fillable = ['type'];

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
