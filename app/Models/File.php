<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class File extends Model
{
    protected $fillable = ['name', 'path'];
}
