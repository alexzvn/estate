<?php

namespace App\Http\Controllers\Manager\Post;

use App\Enums\PostType;
use App\Enums\PostStatus;
use Illuminate\Http\Request;
use App\Http\Requests\Manager\Post\ClonePost;
use App\Http\Requests\Manager\Post\UpdatePost;
use App\Http\Requests\Manager\Post\StoreRequest;
use App\Http\Controllers\Manager\Post\PostController;
use App\Services\System\Post\Fee;
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
            ->with(['categories', 'district'])
            ->filter($request)
            ->newest()
            ->paginate(20);

        $this->shareCategoriesProvinces();

        return view('dashboard.post.online.list', compact('posts'));
    }

    public function fetch(string $id, Online $post)
    {
        $this->authorize('manager.post.online.view');

        return $post->with(['user', 'files'])
            ->findOrFail($id);
    }

    public function view(string $id, Online $post)
    {
        $this->authorize('manager.post.online.view');

        $post = $post->with(['categories', 'user', 'files'])->findOrFail($id);

        $this->shareCategoriesProvinces();

        return view('dashboard.post.online.edit', [
            'post' => $post,
        ]);
    }

    public function create()
    {
        $this->authorize('manager.post.online.create');

        $this->shareCategoriesProvinces();

        return view('dashboard.post.online.create');
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

        return redirect(route('manager.post.online'))
            ->with('success', 'Tạo mới thành công');
    }

    public function update(string $id, UpdatePost $request)
    {
        $this->authorize('manager.post.online.modify');

        $post = Online::findOrFail($id);

        $post = Online::update($post, $request->all());

        $this->syncUploadFiles($post, $request);

        return back()->with('success', 'Cập nhật thành công');
    }

    public function cloneSaveOrigin(string $id, ClonePost $request)
    {
        $this->authorize('manager.post.online.clone');

        $post = Online::findOrFail($id)->replicate();

        $post->type = PostType::PostFee;

        $request->user()->posts()->save($post);

        $post->verifier_id = user()->id;

        $post->categories()->attach(Online::find($id)->category_ids ?? []);

        Fee::update($post, $request->all());

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

        $post = Online::findOrFail($id);

        $post->verifier_id = user()->id;

        Online::update($post, $request->all());

        $post->fill(['type' => PostType::PostFee])->save();

        return response([
            'success' => true,
            'data' => 'Đã duyệt xóa gốc',
        ]);
    }

    public function reverseMany(Request $request)
    {
        $this->authorize('manager.post.online.reserve');

        Online::reverseMany($request->ids ?? []);

        return back()->with('success', 'Đã đảo các mục yêu cầu');
    }

    public function deleteMany(Request $request)
    {
        $this->authorize('manager.post.online.delete');

        Online::deleteMany($request->ids ?? []);

        return back()->with('success', 'Đã xóa các mục yêu cầu');
    }
}
