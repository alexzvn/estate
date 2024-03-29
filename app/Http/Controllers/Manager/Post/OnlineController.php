<?php

namespace App\Http\Controllers\Manager\Post;

use App\Enums\PostStatus;
use App\Enums\PostType;
use Illuminate\Http\Request;
use App\Repository\Permission;
use App\Services\System\Post\Fee;
use App\Services\System\Post\Online;
use App\Http\Requests\Manager\Post\ClonePost;
use App\Http\Requests\Manager\Post\UpdatePost;
use App\Http\Requests\Manager\Post\StoreRequest;
use App\Http\Controllers\Manager\Post\PostController;
use App\Models\Keyword;
use App\Models\Post;
use App\Models\ScoutFilter\PostFilter;

class OnlineController extends PostController
{
    public function index(Request $request)
    {
        $this->authorize('manager.post.online.view');

        if ($query = $request->get('query')) {
            $posts = Post::search($query);

            PostFilter::filter($posts, $request);

            $posts->where('type', PostType::Online)->orderBy('publish_at', 'desc');
        } else {
            $posts = Online::newest()->filter($request);
        }

        $posts->with([
                'province',
                'district',
                'categories',
                'whitelist',
                'tracking',
                'user'
            ]);

        $this->shareCategoriesProvinces();

        return view('dashboard.post.online.list', [
            'posts' => $posts->paginate(40),
            'keywords' => Keyword::all()
        ]);
    }

    public function trashed(Request $request)
    {
        $this->authorize('manager.post.online.view');

        $posts = Online::onlyTrashed()
            ->with(['categories', 'district'])
            ->filter($request)
            ->newest()
            ->paginate();

        $this->shareCategoriesProvinces();

        return view('dashboard.post.online.list', [
            'posts' => $posts,
            'staff' => Permission::findUsersHasPermission('manager.dashboard.access'),
        ]);
    }

    public function fetch(string $id, Online $post)
    {
        $this->authorize('manager.post.online.view');

        return $post->with(['user', 'files', 'categories'])
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

        $post->forceFill([
            'status' => PostStatus::Published,
            'publish_at' => now(),
            'user_id' => user()->id
        ])->save();

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

        $origin = Online::findOrFail($id);

        Fee::update($origin->replicate(['category_ids']) , $request->all())
            ->forceFill([
                'user_id' => user()->id,
                'verifier_id' => user()->id,
                'publish_at' => now(),
            ])->save();

        $origin->forceFill(['approve_fee' => true])->save();

        return response([
            'success' => true,
            'data' => 'Đã duyệt lưu gốc'
        ]);
    }

    public function cloneDeleteOrigin(string $id, ClonePost $request)
    {
        $this->authorize('manager.post.online.clone');

        Fee::update(Online::findOrFail($id), $request->all())
            ->forceFill([
                'verifier_id' => user()->id,
                'user_id'     => user()->id,
                'publish_at'  => now(),
            ])->save();

        return response([
            'success' => true,
            'data' => 'Đã duyệt xóa gốc',
        ]);
    }

    public function reverseMany(Request $request)
    {
        $this->authorize('manager.post.online.reserve');

        $count = Online::reverseMany($request->ids ?? []);

        return back()->with('success', "Đã đảo $count mục yêu cầu");
    }

    public function deleteMany(Request $request)
    {
        $this->authorize('manager.post.online.delete');

        Online::deleteMany($request->ids ?? []);

        return back()->with('success', 'Đã xóa các mục yêu cầu');
    }

    public function forceDeleteMany(Request $request)
    {
        $this->authorize('manager.post.online.delete.force');

        Online::forceDeleteMany($request->ids ?? []);

        return back()->with('success', 'Đã xóa các mục yêu cầu');
    }
}
