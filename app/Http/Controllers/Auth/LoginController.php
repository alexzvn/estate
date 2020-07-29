<?php

namespace App\Http\Controllers\Auth;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Repository\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers {
        logout as public traitLogout;
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $user = User::where($this->username(), $request->{$this->username()})->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            $this->incrementLoginAttempts($request);
            return $this->sendFailedLoginResponse($request);
        }

        if ($user->cannot('login.multiple.devices') && $user->hasDifferenceOnline()) {
            return $this->sendHasSessionLoginResponse($request);
        }

        if ($user->cannot('login.multiple.devices')) {
            $this->storeSessionId($request, $user);
        }

        Auth::login($user, $user->can('login.multiple.devices'));

        return $user->can('manager.dashboard.access') ?
            redirect(RouteServiceProvider::MANAGER) :
            $this->sendLoginResponse($request);
    }

    public function logout(Request $request)
    {
        if ($user = $request->user()) {
            $user->emptySession();
        }

        return $this->traitLogout($request);
    }

    protected function authenticated()
    {
        if (Auth::user()->cannot('login.multiple.devices')) {
            Auth::logoutOtherDevices(request('password'));
        }
    }

    protected function storeSessionId(Request $request, $user)
    {
        $user->forceFill(['session_id' => $request->session()->getId()])->save();
    }

    protected function sendHasSessionLoginResponse(Request $request)
    {
        $request->session()->flash('reject.title', 'Tài khoản này đã đăng nhập từ nơi khác.');
        $request->session()->flash('reject.message', 'Xin hãy đăng xuất tài khoản trên thiết bị cũ hoặc đợi '.\App\Models\User::SESSION_TIMEOUT.' phút để đăng nhập lại.');

        return view('auth.login');
    }

    protected function username()
    {
        return 'phone';
    }
}
