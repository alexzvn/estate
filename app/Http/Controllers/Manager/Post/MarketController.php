<?php

namespace App\Http\Controllers\Manager\Post;

use App\Enums\PostType;
use App\Repository\Post;
use Illuminate\Http\Request;

class MarketController extends PostController
{
    public function index(Request $request)
    {
        $posts = Post::with(['metas.province', 'metas.district'])
            ->with(['categories', 'user', 'files'])
            ->where('type', PostType::PostMarket)
            ->filterRequest($request)
            ->orderBy('publish_at', 'desc')
            ->paginate(30);

        $this->shareCategoriesProvinces();

        return view('dashboard.post.market.list', compact('posts'));
    }
}
