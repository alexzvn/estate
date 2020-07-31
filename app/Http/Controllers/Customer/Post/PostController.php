<?php

namespace App\Http\Controllers\Customer\Post;

use App\Enums\PostMeta;
use App\Enums\PostStatus;
use App\Enums\PostType;
use App\Repository\Post;
use App\Repository\Category;
use App\Http\Controllers\Customer\Post\BaseController;
use App\Http\Requests\Customer\Post\StorePost;
use App\Repository\Meta;

class PostController extends BaseController
{
    public function index()
    {
        return view('customer.home',[
            'posts' => $this->defaultPost()->paginate(20),
        ]);
    }

    public function fee()
    {
        return view('customer.post.fee', [
            'posts' => $this->defaultPost()->where('type', PostType::PostFee)->paginate(20)
        ]);
    }

    public function online()
    {
        return view('customer.post.online', [
            'posts' => $this->defaultPost()->where('type', PostType::Online)->paginate(20)
        ]);
    }

    public function store(StorePost $request, Post $post)
    {
        $post = $post->fill($request->all())
            ->fill([
                'content' => clean($post->content),
                'status'  => PostStatus::Pending
            ]);

        $post = $this->customer->posts()->save($post);

        $post->metas()->saveMany(Meta::fromMany([
            PostMeta::Price => (int) $request->price,
            PostMeta::Phone => $request->phone,
        ]));

        if ($cat = Category::find($request->category)) {
            $post->categories()->attach($cat);
        }

        return response([
            'code' => 200,
            'success' => true,
            'data' => 'Đăng tin thành công',
        ]);
    }

    public function market()
    {
        
    }

    public function view(string $id)
    {
        $post = $this->defaultPost()->where('_id', $id)->firstOrFail();

        return view('customer.components.post-content', [
            'post' => $post,
            'customer' => $this->customer,
            'meta' => $post->loadMeta()->meta
        ]);
    }

    /**
     * default select data from post collection
     *
     * @return \Jenssegers\Mongodb\Eloquent\Builder
     */
    private function defaultPost()
    {
        $access = $this->access;

        $categories = Category::flat($this->accessCategories())
            ->map(function ($cat) {
                return $cat->id;
            });

        return Post::withRelation()
            ->filterRequest(request())
            ->published()
            ->whereIn('type', $access->getPostTypes())
            ->whereNotIn('_id', $this->customer->post_blacklist_ids ?? [])
            ->filterRequest([
                'categories' => $categories,
                'provinces'  => $access->getProvinces()
            ]);
    }
}
