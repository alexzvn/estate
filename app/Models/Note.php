<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\Traits\Auditable as TraitsAuditable;

class Note extends Model implements Auditable
{
    use TraitsAuditable;

    const NAME = 'ghi chú';

    protected $fillable = ['content'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function adder()
    {
        return $this->belongsTo(User::class);
    }
}
