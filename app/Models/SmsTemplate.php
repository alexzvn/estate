<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class SmsTemplate extends Model
{
    protected $fillable = [
        'name',
        'content',
    ];

    /**
     * Undocumented function
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function history()
    {
        return $this->hasMany(SmsHistory::class);
    }

    /**
     * Whatever who create this template
     *
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
