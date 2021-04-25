<?php

namespace App\Http\Controllers\Manager\Post;

use App\Enums\PostType;
use App\Repository\Post;
use App\Enums\PostStatus;
use Illuminate\Http\Request;
use App\Repository\Permission;
use App\Services\System\Post\Market;
use App\Models\ScoutFilter\PostFilter;
use App\Http\Requests\Manager\Post\Market\UpdatePost;

class MarketController extends PostController
{
    public function index(Request $request)
    {
        if ($query = $request->get('query')) {
            $posts = Post::search($query);

            PostFilter::filter($posts, $request);

            $posts->where('type', PostType::PostMarket)->orderBy('publish_at', 'desc');
        } else {
            $posts = Market::newest()->filter($request);
        }

        $posts->with(['province', 'district', 'categories', 'user', 'files']);

        $this->shareCategoriesProvinces();

        return view('dashboard.post.market.list', [
            'posts' => $posts->paginate(20),
            'staff' => Permission::findUsersHasPermission('manager.dashboard.access')
        ]);
    }

    public function update(string $id, UpdatePost $request)
    {
        $post = Post::findOrFail($id);

        $post = Market::update($post, $request->all())
            ->forceFill([
                'user_id' => user()->id,
                'status' => PostStatus::Published,
                'publish_at' => now()
            ]);

        $this->syncUploadFiles(tap($post)->save(), $request);

        return back()->with('success', 'Cập nhật thành công');
    }

    public function store(UpdatePost $request)
    {
        $post = Market::create($request->all())
            ->forceFill([
                'user_id' => user()->id,
                'status' => PostStatus::Published,
                'publish_at' => now()
            ]);

        $this->syncUploadFiles(tap($post)->save(), $request);

        return back()->with('success', 'ok');
    }

    public function fetch(string $id)
    {
        $this->authorize('manager.post.market.view');

        return Market::findOrFail($id)->load(['files', 'user', 'categories']);
    }

    public function delete(string $id)
    {
        $this->authorize('manager.post.market.delete');

        Market::findOrFail($id)->delete();

        return back()->with('success', 'Đã xóa tin thị trường này');
    }
}
