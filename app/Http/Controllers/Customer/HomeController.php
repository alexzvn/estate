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
        $post = Post::withRelation()
            ->published()
            ->filterRequest($request)
            ->select(['title', 'publish_at']);

        return view('customer.home', [
            'posts' => $post->paginate(10),
        ]);
    }

    public function viewPost(string $id)
    {
        $post = Post::withRelation()->published()->findOrFail($id);

        return view('customer.components.post-content', [
            'post' => $post,
            'meta' => $post->loadMeta()->meta
        ]);
    }
}
