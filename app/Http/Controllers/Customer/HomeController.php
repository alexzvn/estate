<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function price()
    {
        return view('customer.price', [
            'provinces' => collect(),
            'categories' => collect(),
        ]);
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
