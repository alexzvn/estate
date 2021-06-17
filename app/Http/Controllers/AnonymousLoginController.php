<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnonymousLoginController extends Controller
{
    public function login(User $user, Request $request)
    {
        if ($request->hasValidSignature()) {
            User::withoutEvents(function () use ($user) {
                Auth::login($user, false);
            });

            $request->session()->regenerate();
        }

        return redirect('/');
    }
}
