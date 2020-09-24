<?php

namespace App\Http\Controllers\Manager\Post;

use App\Enums\PostStatus;
use Illuminate\Http\Request;
use App\Services\System\Post\Fee;
use App\Http\Requests\Manager\Post\StoreRequest;
use App\Http\Requests\Manager\Post\UpdatePost;

class FeeController extends PostController
{
    public function index(Request $request)
    {
        $this->authorize('manager.post.fee.view');

        $posts = Fee::with(['province', 'district', 'categories', 'user'])
            ->filter($request)
            ->newest()
            ->paginate(30);

        $this->shareCategoriesProvinces();

        return view('dashboard.post.fee.list', [
            'posts' => $posts
        ]);
    }

    public function view(string $id, Fee $post)
    {
        $this->authorize('manager.post.fee.view');

        $this->shareCategoriesProvinces();

        return view('dashboard.post.fee.edit', [
            'post' => $post->findOrFail($id)
        ]);
    }

    public function fetch(string $id, Fee $post)
    {
        $this->authorize('manager.post.fee.view');

        $post = $post->findOrFail($id);

        return $post->load(['files', 'user']);
    }

    public function create()
    {
        $this->authorize('manager.post.fee.create');

        $this->shareCategoriesProvinces();

        return view('dashboard.post.fee.create');
    }

    public function store(StoreRequest $request)
    {
        $this->validate($request, ['commission' => 'required|string']);

        $post = Fee::create($request->all());

        $this->syncUploadFiles($post, $request);

        $request->user()->posts()->save($post);

        if ($request->status == PostStatus::Published && empty($post->publish_at)) {
            $post->publish_at = now(); $post->save();
        }

        return redirect(route('manager.post.fee.view', ['id' => $post->id]))
            ->with('success', 'Tạo mới thành công');
    }

    public function update(string $id, UpdatePost $request)
    {
        $this->validate($request, ['commission' => 'required|string']);

        $post = Fee::update(Fee::findOrFail($id), $request->all());

        $this->syncUploadFiles($post, $request);

        if ($request->status == PostStatus::Published && empty($post->publish_at)) {
            $post->publish_at = now(); $post->save();
        }

        return redirect(route('manager.post.fee.view', ['id' => $post->id]))
            ->with('success', 'Tạo mới thành công');
    }

    public function deleteMany(Request $request)
    {
        $this->authorize('manager.post.fee.delete.many');

        Fee::deleteMany($request->ids ?? []);

        return back()->with('success', 'Đã xóa các mục yêu cầu');
    }

    public function reserveMany(Request $request)
    {
        $this->authorize('manager.post.fee.delete.many');

        Fee::reserveMany($request->ids ?? []);

        return back()->with('success', 'Đã đảo các mục yêu cầu');
    }
}
