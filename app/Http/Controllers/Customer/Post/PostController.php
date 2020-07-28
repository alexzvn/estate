<?php

namespace App\Http\Controllers\Customer\Post;

use App\Enums\PostType;
use App\Repository\Post;
use App\Repository\Category;
use App\Http\Controllers\Customer\Post\BaseController;

class PostController extends BaseController
{
    public function index()
    {
        return view('customer.home',[
            'posts' => $this->defaultPost()->paginate(),
        ]);
    }

    public function fee()
    {
        return view('customer.post.fee', [
            'posts' => $this->defaultPost()->where('type', PostType::PostFee)->paginate()
        ]);
    }

    public function online()
    {
        return view('customer.post.online', [
            'posts' => $this->defaultPost()->where('type', PostType::Online)->paginate()
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
            ->whereNotIn('_id', $this->customer->post_blacklist_ids)
            ->filterRequest([
                'categories' => $categories,
                'provinces'  => $access->getProvinces()
            ]);
    }
}
