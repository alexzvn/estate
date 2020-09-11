<?php

namespace App\Http\Controllers\Manager\Post;

use App\Repository\File;
use App\Repository\Post;
use App\Models\Whitelist;
use App\Repository\Category;
use Illuminate\Http\Request;
use App\Repository\Location\District;
use App\Repository\Location\Province;
use App\Services\System\Post\PostService;
use App\Http\Controllers\Manager\Controller;
use App\Http\Requests\Manager\Post\UpdatePost;
use App\Http\Requests\Manager\Post\DeleteManyPost;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('manager.post.view');

        $posts = Post::with(['province', 'district','categories', 'user'])
            ->filter($request)
            ->newest()
            ->paginate(30);

        $this->shareCategoriesProvinces();

        return view('dashboard.post.list', compact('posts'));
    }

    public function trashed(Request $request)
    {
        $this->authorize('manager.post.view');

        $posts = Post::onlyTrashed()
            ->with(['categories'])
            ->filter($request)
            ->newest()
            ->paginate(20);

        $this->shareCategoriesProvinces();

        return view('dashboard.post.list', compact('posts'));
    }

    public function view(string $id, Post $post)
    {
        $this->authorize('manager.post.view');

        $post = $post->with(['categories', 'user'])->findOrFail($id);
        $provinces = Province::with('districts')->active()->get();

        return view('dashboard.post.edit', [
            'post' => $post,
            'meta' => $post->meta,
            'provinces' => $provinces,
            'districts' => District::all(),
            'categories' => Category::with('children.children.children')->parentOnly()->get(),
        ]);
    }

    public function create()
    {
        $this->authorize('manager.post.create');

        return view('dashboard.post.create', [
            'provinces' => Province::with('districts')->active()->get(['name']),
            'categories' => Category::with('children.children.children')->parentOnly()->get(),
        ]);
    }

    public function deleteMany(DeleteManyPost $request)
    {
        Post::whereIn('_id', $request->ids ?? [])->get()->each(function ($post)
        {
            $post->delete();
        });

        return back()->with('success', 'Đã xóa các mục yêu cầu');
    }

    public function reverseMany(Request $request)
    {
        Post::whereIn('_id', $request->ids ?? [])->get()->each(function ($post)
        {
            $post->forceFill(['publish_at' => now(), 'reverser' => true])->save();
        });

        return back()->with('success', 'Đã đảo các tin đã chọn');
    }

    public function update(string $id, UpdatePost $request)
    {
        $post = Post::findOrFail($id);

        $post = PostService::update($post, $request->all());

        $this->syncUploadFiles($post, $request);

        return back()->with('success', 'Cập nhật thành công');
    }

    protected function shareCategoriesProvinces()
    {
        view()->share('categories', Category::parentOnly()->with('children')->get());
        view()->share('provinces', Province::active()->with('districts')->get());
        view()->share('whitelist', Whitelist::all());
    }

    private function syncUploadFiles($post, Request $request)
    {
        $ids = collect($request->image_ids ?? []);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $uploaded = File::create([
                    'name' => $file->getFilename(),
                    'path' => $file->store('media/images', 'public')
                ]);

                $ids->push($uploaded->id);
            }
        }

        if ($ids->count()) {
            $post->files()->sync($ids->toArray());
        }
    }
}
