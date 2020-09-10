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
        if (empty($attr->province)) {
            return $next($attr);
        }

        if ($attr->province instanceof Province) {
            $province = $attr->province->id;
        } else if ($province = Province::find($attr->province)) {
            $province = $province->id;
        }

        $attr->province_id = $province ?? null;

        return $next($attr);
    }
}
