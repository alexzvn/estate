<?php

namespace App\Http\Controllers\Manager\Post;

use App\Enums\PostType;
use App\Http\Requests\Manager\Post\Market\UpdatePost;
use App\Repository\Post;
use App\Services\System\Post\Market;
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

    public function update(string $id, UpdatePost $request)
    {
        $post = Post::findOrFail($id);

        $post = Market::update($post, $request->all());

        $this->syncUploadFiles($post, $request);

        return back()->with('success', 'Cập nhật thành công');
    }

    public function store(UpdatePost $request)
    {
        $post = Market::create($request->all());

        $this->syncUploadFiles($post, $request);

        return back()->with('success', 'ok');
    }

    public function fetch(string $id)
    {
        $this->authorize('manager.post.market.view');

        return Market::findOrFail($id)->load(['files', 'user']);
    }

    public function delete(string $id)
    {
        $this->authorize('manager.post.market.delete');

        Market::findOrFail($id)->delete();

        return back()->with('success', 'Đã xóa tin thị trường này');
    }
}
