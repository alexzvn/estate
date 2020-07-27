<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class VerifyPhone
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
        if ($request->user() && $request->user()->hasVerifiedPhone()) {
            return $next($request);
        }

        if ($request->user() && $request->user()->can('*')) {
            return $next($request);
        }

        $request->session()->flash('reject.title', 'Tài khoản của bạn chưa xác thực');
        $request->session()->flash('reject.message', 'Tài khoản của bạn cần xác thực và cấp quyền xem tin. Vui lòng liên hệ hotline để được hướng dẫn.');

        return response()->view('auth.login');
    }
}
