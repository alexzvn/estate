<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Repository\User;
use App\Providers\RouteServiceProvider;
use App\Services\Customer\Customer;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;

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

        $customer = new Customer($user);

        if ($user->cannot('login.multiple.devices') && $user->hasDifferenceOnline()) {

            $customer->createLog([
                'content' => 'Đã cố truy cập tài khoản trên nhiều thiết bị'
            ]);

            return $this->sendHasSessionLoginResponse($request);
        }

        User::withoutEvents(function () {
            Auth::login($user, true);
        });

        $request->session()->regenerate();

        if ($user->cannot('login.multiple.devices')) {
            $this->storeSessionId($request, $user);
        }

        $customer->createLog([
            'content' => 'Đã đăng nhập vào tài khoản'
        ]);

        return $user->can('manager.dashboard.access') ?
            redirect(RouteServiceProvider::MANAGER) :
            $this->sendLoginResponse($request);
    }

    public function logout(Request $request)
    {
        if ($user = $request->user()) {
            $user->emptySession();

            (new Customer($user))->createLog([
                'content' => 'Đã đăng xuất khỏi tài khoản'
            ]);
        }

        $response = null;

        User::withoutEvents(function () use (&$response, $request) {
            $response = $this->traitLogout($request);
        });

        return $response;
    }

    public function quietLogout(Request $request)
    {
        if ($user = $request->user()) {
            $user->emptySession();
        }

        $response = null;

        User::withoutEvents(function () use (&$response, $request) {
            $response = $this->traitLogout($request);
        });

        return $response;
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

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $request)
    {
        $this->clearLoginAttempts($request);

        if ($response = $this->authenticated($request, $this->guard()->user())) {
            return $response;
        }

        return $request->wantsJson()
                    ? new Response('', 204)
                    : redirect()->intended($this->redirectPath());
    }
}
