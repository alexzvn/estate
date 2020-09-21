<?php

namespace App\Http\Resolvers;

use OwenIt\Auditing\Contracts\IpAddressResolver as ContractsIpAddressResolver;

class IpAddressResolver implements ContractsIpAddressResolver
{
    public static function resolve() : string
    {
        return request()->header(
            'X-Forwarded-For',
            request()->ip()
        );
    }
}
