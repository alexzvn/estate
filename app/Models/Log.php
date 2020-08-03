<?php

namespace App\Models;

use App\Models\Traits\CanFilter;
use Illuminate\Database\Eloquent\Builder;
use Jenssegers\Mongodb\Eloquent\Model;

class Log extends Model
{
    use CanFilter;

    protected $fillable = [
        'content', 'link'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function filterPhone(Builder $builder, $phone)
    {
        return $builder->whereHas('user', function (Builder $builder) use ($phone)
        {
            $builder->where('phone', 'like', "%$phone%");
        });
    }
}
