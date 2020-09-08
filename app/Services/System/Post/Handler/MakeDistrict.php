<?php

namespace App\Services\System\Post\Handler;

use App\Contracts\Handler;
use App\Models\Location\District;

class MakeDistrict implements Handler
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
        if (empty($attr->district) && !($attr->district instanceof District)) {
            return $next($attr);
        }

        $attr->district_id = $attr->district->id;

        return $attr;
    }
}
