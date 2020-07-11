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

        if (($user = $request->user()) && $user->can('manager.dashboard.access')) {
            return $next($request);
        }

        return abort(404);
    }
}
