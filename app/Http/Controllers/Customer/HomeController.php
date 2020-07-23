<?php

namespace App\Http\Controllers\Customer;

use App\Repository\Post;
use Illuminate\Support\Str;
use App\Repository\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
        $sellPosts = $this->defaultQuery($request)
            ->filterRequest([
                'categories' => $this->getListCategories('BÁN')
            ]);

        $rentPosts = $this->defaultQuery($request)
            ->filterRequest([
                'categories' => $this->getListCategories('THUÊ')
            ]);

        return view('customer.home', [
            'sellPosts' => $sellPosts->paginate(10),
            'rentPosts' => $rentPosts->paginate(10),
        ]);
    }

    public function getListCategories(string $query)
    {
        $cat = Category::parentOnly()->where('name', 'like', "%$query%")->first();

        if (! $cat) {
            return [];
        }

        return $cat->children()->get()->map(function ($cat)
        {
            return $cat->id;
        });
    }

    public function defaultQuery(Request $request)
    {
        return Post::withRelation()
            ->published()
            ->filterRequest($request)
            ->select(['title', 'publish_at']);
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
