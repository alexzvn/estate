<?php

namespace App\Services\System\Post\Handler;

use App\Contracts\Handler;

class CastPriceToInt implements Handler
{
    /**
     * Clean javascript out of html content
     *
     * @param object $value
     * @param Closure $next
     * @return mixed
     */
    public function handle($attr, \Closure $next)
    {
        if (empty($attr->price)) {
            return $next($attr);
        }

        $attr->price = (int) str_replace(',', '', $attr->price);

        return $next($attr);
    }
}
