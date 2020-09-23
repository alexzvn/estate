<?php

namespace App\Http\Controllers\Manager\Post;

use App\Enums\PostType;
use App\Repository\Post;
use App\Enums\PostStatus;
use App\Repository\Category;
use Illuminate\Http\Request;
use App\Repository\Location\District;
use App\Repository\Location\Province;
use App\Services\System\Post\PostService;
use App\Http\Requests\Manager\Post\ClonePost;
use App\Http\Requests\Manager\Post\UpdatePost;
use App\Http\Requests\Manager\Post\StoreRequest;
use App\Http\Controllers\Manager\Post\PostController;
use App\Services\System\Post\Online;

class OnlineController extends PostController
{
    public function index(Request $request)
    {
        $this->authorize('manager.post.online.view');

        $posts = Online::with(['province', 'district','categories', 'user'])
            ->filter($request)
            ->newest()
            ->paginate(30);

        $this->shareCategoriesProvinces();

        return view('dashboard.post.online.list', compact('posts'));
    }

    public function trashed(Request $request)
    {
        $this->authorize('manager.post.online.view');

        $posts = Online::onlyTrashed()
            ->with(['categories'])
            ->filter($request)
            ->newest()
            ->paginate(20);

        $this->shareCategoriesProvinces();

        return view('dashboard.post.online.list', compact('posts'));
    }

    public function fetch(string $id, Post $post)
    {
        $this->authorize('manager.post.online.view');

        return $post->with(['user', 'files'])
            ->findOrFail($id);
    }

    public function view(string $id, Post $post)
    {
        $this->authorize('manager.post.online.view');

        $post = $post->with(['categories', 'user', 'files'])->findOrFail($id);
        $provinces = Province::with('districts')->active()->get();

        return view('dashboard.post.online.edit', [
            'post' => $post,
            'meta' => $post->meta,
            'provinces' => $provinces,
            'districts' => District::all(),
            'categories' => Category::with('children.children.children')->parentOnly()->get(),
        ]);
    }

    public function create()
    {
        $this->authorize('manager.post.online.create');

        return view('dashboard.post.online.create', [
            'provinces' => Province::with('districts')->active()->get(['name']),
            'categories' => Category::with('children.children.children')->parentOnly()->get(),
        ]);
    }

    public function store(StoreRequest $request)
    {
        $this->authorize('manager.post.online.create');

        $post = Online::create($request->all());

        $this->syncUploadFiles($post, $request);

        $request->user()->posts()->save($post);

        if ($request->status == PostStatus::Published && empty($post->publish_at)) {
            $post->publish_at = now(); $post->save();
        }

        return redirect(route('manager.post.online.view', ['id' => $post->id]))
            ->with('success', 'Tạo mới thành công');
    }

    public function update(string $id, UpdatePost $request)
    {
        $this->authorize('manager.post.online.modify');

        $post = Post::findOrFail($id);

        $post = PostService::update($post, $request->all());

        $this->syncUploadFiles($post, $request);

        return back()->with('success', 'Cập nhật thành công');
    }

    public function cloneSaveOrigin(string $id, ClonePost $request)
    {
        $this->authorize('manager.post.online.clone');

        $post = Post::findOrFail($id)->replicate();

        $request->user()->posts()->save($post);

        if ($request->status == PostStatus::Published) {
            $post->publish_at = now(); $post->save();
        }

        return response([
            'success' => true,
            'data' => 'Đã duyệt lưu gốc'
        ]);
    }

    public function cloneDeleteOrigin(string $id, ClonePost $request)
    {
        $this->authorize('manager.post.online.clone');

        $post = Post::findOrFail($id);

        PostService::update($post, $request->all());

        $post->fill(['type' => PostType::PostFee])->save();

        return response([
            'success' => true,
            'data' => 'Đã duyệt xóa gốc',
        ]);
    }

    public function reverseMany(Request $request)
    {
        $this->authorize('manager.post.online.reserve');

        return parent::reverseMany($request);
    }

    public function deleteMany(Request $request)
    {
        $this->authorize('manager.post.online.delete');

        return parent::deleteMany($request);
    }
}
