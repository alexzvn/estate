<?php

namespace App\Services\System\Post\Handler;

use App\Contracts\Handler;
use App\Models\Location\Province;

class MakeProvince implements Handler
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
        if (empty($attr->province) && !($attr->province instanceof Province)) {
            return $next($attr);
        }

        $attr->province_id = $attr->province->id;

        return $attr;
    }
}
