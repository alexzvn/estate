<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\Controllers\Auth\LoginController;

class EnsureCustomerSameSession
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
        if (($user = $request->user()) && $user->cannot('login.multiple.devices')) {
            $user->session_id !== $request->session()->getId();

            app(LoginController::class)->quietLogout($request);
        }

        return $next($request);
    }
}
