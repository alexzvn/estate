<?php

namespace App\Models;

use App\Models\Traits\Auditable as TraitsAuditable;
use App\Models\Traits\CanFilter;
use App\Models\Traits\HasNote;
use Jenssegers\Mongodb\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Blacklist extends Model implements Auditable
{
    use HasNote, CanFilter, TraitsAuditable;

    protected $fillable = ['phone', 'source'];

    protected $filterable = ['phone', 'source'];

    const NAME = 'danh sách đen';

    public function posts()
    {
        return $this->hasMany(Post::class, 'phone', 'phone');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
