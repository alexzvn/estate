<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Note extends Model
{
    protected $fillable = ['content'];
}
