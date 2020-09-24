<?php

namespace App\Services\System\Post\Handler;

use App\Contracts\Handler;

class NormalizePhone implements Handler
{
    public function handle($value, \Closure $next)
    {
        if (isset($value->phone)) {
            $value->phone = str_replace('.', '', $value->phone);
        }

        return $next($value);
    }
}
