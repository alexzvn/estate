<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Repository\Post;
use Illuminate\Http\Request;

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
    public function index(Request $request)
    {
        return view('customer.home', [
            'posts' => Post::published()->filterRequest($request)->select(['title', 'publish_at'])->paginate(10),
        ]);
    }

    public function viewPost(string $id)
    {
        $post = Post::published()->findOrFail($id);

        return view('customer.components.post-content', [
            'post' => $post,
            'meta' => $post->loadMeta()->meta
        ]);
    }
}
