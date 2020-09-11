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
        if (empty($attr->district)) {
            return $next($attr);
        }

        if ($attr->district instanceof District) {
            $district = $attr->district->id;
        } else if ($district = District::find($attr->district)) {
            $district = $district->id;
        }

        $attr->district_id = $district ?? null;

        return $next($attr);
    }
}
