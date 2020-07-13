<?php

namespace App\Http\Controllers\Manager\Post;

use App\Enums\PostMeta;
use App\Repository\Category;
use Illuminate\Http\Request;
use App\Repository\Location\Province;
use App\Http\Controllers\Manager\Controller;
use App\Http\Requests\Manager\Post\StoreRequest;
use App\Repository\Meta;
use App\Repository\Post;
use Mews\Purifier\Facades\Purifier;

class PostController extends Controller
{
    public function create(Request $request)
    {
        $this->authorize('manager.post.create');

        return view('dashboard.post.create', [
            'provinces' => Province::with('districts')->active()->get(['name']),
            'categories' => Category::with('children')->parentOnly()->get(['name']),
        ]);
    }

    public function store(StoreRequest $request)
    {
        $this->authorize('manager.post.create');

        $post = Post::create(
            array_merge($request->all(), [
                'content' => Purifier::clean($request->post_content)
            ])
        );

        $post->categories()->save(Category::find($request->category));

        $post->metas()->saveMany($this->makeMetas([
            PostMeta::Phone      => str_replace('.', '', $request->phone),
            PostMeta::Price      => (int) str_replace(',', '', $request->price),
            PostMeta::Province   => $request->province,
            PostMeta::District   => $request->district,
            PostMeta::Commission => $request->commission
        ]));

        return $post;
    }

    protected function makeMetas(array $metas)
    {
        foreach ($metas as $name => $value) {
            $meta[] = Meta::fill([
                'name' => $name,
                'value' => $value
            ]);
        }

        return collect($meta ?? []);
    }
}
