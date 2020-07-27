<?php

namespace App\Http\Controllers\Customer;

use App\Repository\Post;
use Illuminate\Support\Str;
use App\Repository\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repository\Location\Province;

class HomeController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(PostController $post)
    {
        return $post->index();
    }
}
