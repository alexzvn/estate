<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthUpdateLastSeen
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
        if (($user = Auth::user()) && $user->cannot('login.multiple.devices')) {
            $user->forceFill([
                'last_seen' => now()
            ])->save();
        }

        return $next($request);
    }
}
