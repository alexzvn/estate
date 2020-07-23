<?php

namespace App\Http\Controllers\Manager\Post;

use App\Enums\PostMeta;
use App\Repository\Category;
use Illuminate\Http\Request;
use App\Repository\Location\Province;
use App\Http\Controllers\Manager\Controller;
use App\Http\Requests\Manager\Post\StoreRequest;
use App\Http\Requests\Manager\Post\UpdatePost;
use App\Repository\Location\District;
use App\Repository\Meta;
use App\Repository\Post;
use Mews\Purifier\Facades\Purifier;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('manager.post.view');

        $posts = Post::with(['metas.province', 'categories'])
            ->select(['name', 'title'])
            ->filterRequest($request)
            ->paginate(20);

        return view('dashboard.post.list', compact('posts'));
    }

    public function trashed(Request $request)
    {
        $this->authorize('manager.post.view');

        $posts = Post::onlyTrashed()
            ->with(['metas.province', 'categories'])
            ->select(['name', 'title'])
            ->filterRequest($request)
            ->paginate(20);

        return view('dashboard.post.list', compact('posts'));
    }

    public function view(string $id, Post $post)
    {
        $this->authorize('manager.post.view');

        $post = $post->with(['categories', 'metas'])->findOrFail($id)->loadMeta();
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

    public function update(string $id, UpdatePost $request)
    {
        $post = Post::findOrFail($id);

        $post->metas()->delete();

        $post->fill(
            array_merge($request->all(), [
                'content' => Purifier::clean($request->post_content)
            ])
        )->save();

        $post->categories()->save(Category::find($request->category));

        $post->metas()->saveMany(Meta::fromMany([
            PostMeta::Phone      => str_replace('.', '', $request->phone),
            PostMeta::Price      => (int) str_replace(',', '', $request->price),
            PostMeta::Province   => $request->province,
            PostMeta::District   => $request->district,
            PostMeta::Commission => $request->commission
        ]));

        return back()->with('success', 'Cập nhật thành công');
    }

    public function store(StoreRequest $request)
    {
        $post = Post::create(
            array_merge($request->all(), [
                'content' => Purifier::clean($request->post_content)
            ])
        );

        $post->categories()->save(Category::find($request->category));

        $post->metas()->saveMany(Meta::fromMany([
            PostMeta::Phone      => str_replace('.', '', $request->phone),
            PostMeta::Price      => (int) str_replace(',', '', $request->price),
            PostMeta::Province   => $request->province,
            PostMeta::District   => $request->district,
            PostMeta::Commission => $request->commission
        ]));

        return redirect(route('manager.post.view', ['id' => $post->id]))
            ->with('success', 'Tạo mới thành công');
    }
}
