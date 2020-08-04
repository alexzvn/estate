<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\Controllers\Auth\LoginController;

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

        app(LoginController::class)->quietLogout($request);

        $request->session()->flash('reject.title', 'Tài khoản của bạn chưa xác thực danh tính');
        $request->session()->flash('reject.message', 'Vui lòng liên hệ CSKH 096.55.33.958 để xác thực và đăng ký gói xem nguồn chính chủ. xin cảm ơn!');

        return redirect(route('login'));
    }
}
