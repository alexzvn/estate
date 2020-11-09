<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanType extends Model
{
    protected $fillable = ['type'];

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
