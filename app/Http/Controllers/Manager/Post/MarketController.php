<?php

namespace App\Http\Controllers\Manager\Post;

use App\Enums\PostType;
use App\Http\Requests\Manager\Post\Market\UpdatePost;
use App\Repository\Post;
use App\Services\System\Post\PostService;
use Illuminate\Http\Request;

class MarketController extends PostController
{
    public function index(Request $request)
    {
        $posts = Post::with(['province', 'district'])
            ->with(['categories', 'user', 'files'])
            ->where('type', PostType::PostMarket)
            ->filter($request)
            ->orderBy('publish_at', 'desc')
            ->paginate(30);

        $this->shareCategoriesProvinces();

        return view('dashboard.post.market.list', compact('posts'));
    }

    public function updateMarket(string $id, UpdatePost $request)
    {
        $post = Post::findOrFail($id);

        $post = PostService::update($post, $request->all());

        $this->syncUploadFiles($post, $request);

        return back()->with('success', 'Cập nhật thành công');
    }
}
