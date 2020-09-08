<?php

namespace App\Models;

use App\Models\Traits\CanFilter;
use Illuminate\Database\Eloquent\Builder;
use Jenssegers\Mongodb\Eloquent\Model;

class Report extends Model
{
    use CanFilter;

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function filterPhone(Builder $builder, string $phone)
    {
        $builder->orWhereHas('user', function (Builder $builder) use ($phone)
        {
            $builder->where('phone', 'like', "%$phone%");
        });

        $builder->orWhereHas('post', function (Builder $builder) use ($phone)
        {
            $builder->where('phone', $phone);
        });
    }
}
