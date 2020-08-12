<?php

namespace App\Models;

use App\Models\Traits\HasNote;
use Jenssegers\Mongodb\Eloquent\Model;

class Blacklist extends Model
{
    use HasNote;

    protected $fillable = ['phone'];
}
