<?php

namespace App\Http\Controllers\Manager\User;

use App\Repository\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Manager\Controller;

class UserController extends Controller
{
    public function index(Request $request)
    {
        return view('dashboard.user.index', [
            'users' => User::filterRequest($request)->latest()->paginate(20)
        ]);
    }
}
