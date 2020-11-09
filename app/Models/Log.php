<?php

namespace App\Models;

use App\Models\Traits\CacheDefault;
use App\Models\Traits\CanFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use CanFilter, CacheDefault;

    protected $fillable = [
        'content', 'link'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isViewPost()
    {
        return preg_match('/^Đã xem tin:/', $this->content);
    }

    public function filterPhone(Builder $builder, $phone)
    {
        return $builder->whereHas('user', function (Builder $builder) use ($phone)
        {
            $builder->where('phone', 'like', "%$phone%");
        });
    }
}
