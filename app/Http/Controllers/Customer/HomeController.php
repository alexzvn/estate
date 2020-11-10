<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function __construct() {
        parent::__construct();

        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return redirect(route('post.online'));
    }
}
