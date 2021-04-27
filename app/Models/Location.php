<?php

namespace App\Models;

use Illuminate\Support\Str;
use App\Models\Traits\CacheDefault;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

abstract class Location extends Model
{
    use HasFactory, CacheDefault;

    public function toRegex()
    {
        $name = preg_replace("/^($this->type)/i", '', $this->name, 1);

        $name = trim($name);

        return "/(?:^|\W)$name(?:$|\W)/i";
    }

    public function toAsciiRegex()
    {
        return Str::ascii($this->toRegex());
    }

    public function match(string $content)
    {
        return (bool) preg_match($this->toRegex(), $content);
    }
}
