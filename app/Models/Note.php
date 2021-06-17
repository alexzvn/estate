<?php

namespace App\Models;

use OwenIt\Auditing\Contracts\Auditable;
use App\Models\Traits\Auditable as TraitsAuditable;
use App\Models\Traits\CacheDefault;
use Illuminate\Database\Eloquent\Model;

class Note extends Model implements Auditable
{
    use TraitsAuditable;

    const NAME = 'ghi chú';

    protected $fillable = ['content'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function notable()
    {
        return $this->morphTo();
    }
}
