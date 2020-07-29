<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class EnsureUserNotBanned
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
        if ($request->user() && $request->user()->can('*')) {
            return $next($request);
        }

        if ($request->user() && $request->user()->isBanned()) {
            Auth::logout();

            $request->session()->flash('reject.title', 'Tài khoản của bạn đã bị khóa');
            $request->session()->flash('reject.message', 'Tài khoản của bạn đã bị khóa vì một số lý do. Vui lòng liên hệ hotline để được giải đáp thắc mắc.');
        }

        return $next($request);
    }
}
