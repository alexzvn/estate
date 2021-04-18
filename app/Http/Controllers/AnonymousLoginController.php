<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnonymousLoginController extends Controller
{
    public function login(User $user, Request $request)
    {
        if (! $this->shouldLogin($request)) {
            abort(404);
        }

        Auth::login($user, false);

        return redirect('/');
    }

    protected function shouldLogin(Request $request)
    {
        return $request->hasValidSignature();
    }
}
