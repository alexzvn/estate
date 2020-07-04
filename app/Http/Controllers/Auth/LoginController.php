<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
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

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            $this->incrementLoginAttempts($request);
            return $this->sendFailedLoginResponse($request);
        }

        if ($this->shouldPreventLoginSession($request, $user)) {
            return $this->sendHasSessionLoginResponse($request);
        }

        Auth::login($user, $request->has('remember'));

        $this->storeSessionId($request, $user);

        return $this->sendLoginResponse($request);
    }

    public function logout(Request $request)
    {
        if ($user = $request->user()) {
            $user->session_id = null;
            $user->save();
        }

        return $this->traitLogout($request);
    }

    protected function shouldPreventLoginSession(Request $request, User $user)
    {
        return (
            $this->hasLoginSession($user) &&
            ! $this->isSessionOver($user) &&
            $this->hasDifferenceSession($request, $user)
        );
    }

    protected function authenticated()
    {
        Auth::logoutOtherDevices(request('password'));
    }

    protected function storeSessionId(Request $request, User $user)
    {
        $user->forceFill(['session_id' => $request->session()->getId()])->save();
    }

    protected function sendHasSessionLoginResponse(Request $request)
    {
        // todo #1 send has session activity response
    }

    private function hasLoginSession(User $user)
    {
        return ! empty($user->session_id);
    }

    private function hasDifferenceSession(Request $request, User $user)
    {
        return $user->session_id !== $request->session()->getId();
    }

    private function isSessionOver(User $user)
    {
        return now()->greaterThan(
            $user->last_seen->addMinutes(User::SESSION_TIMEOUT)
        );
    }
}
