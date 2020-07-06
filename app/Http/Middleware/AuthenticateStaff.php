<?php

namespace App\Http\Middleware;

use App\Enums\Role;
use Closure;

class AuthenticateStaff
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! ($user = $request->user())) {
            return abort(403);
        }

        if ($user->hasAnyRole([Role::SuperAdmin, Role::Staff])) {
            return $next($request);
        }

        return abort(403);
    }
}
