<?php

namespace App\Http\Controllers\Customer\Post;

use App\Enums\PostStatus;
use App\Enums\PostType;
use App\Repository\Post;
use App\Repository\Category;
use App\Http\Controllers\Customer\Post\BaseController;
use App\Http\Requests\Customer\Post\StorePost;
use App\Repository\Meta;
use Illuminate\Http\Request;

class PostController extends BaseController
{
    public function fee(Request $request)
    {
        $type = PostType::PostFee;

        $this->customer->createLog([
            'content' => 'Đã truy cập '. $type,
            'link'    => $request->fullUrl()
        ]);

        $this->shareView($type);

        return view('customer.post.fee', [
            'canAccess' => $this->access->canAccess($type),
            'posts' => $this->defaultPost($type)->paginate(20)
        ]);
    }

    public function online(Request $request)
    {
        $type = PostType::Online;

        $this->customer->createLog([
            'content' => 'Đã truy cập '. $type,
            'link'    => $request->fullUrl()
        ]);

        $this->shareView($type);

        return view('customer.post.online', [
            'canAccess' => $this->access->canAccess($type),
            'posts' => $this->defaultPost($type)->paginate(20)
        ]);
    }

    public function market(Request $request)
    {
        $type = PostType::PostMarket;

        $this->customer->createLog([
            'content' => 'Đã truy cập '. $type,
            'link'    => $request->fullUrl()
        ]);

        $this->shareView($type);

        return view('customer.post.market', [
            'canAccess' => $this->access->canAccess($type),
            'posts' => $this->defaultPost($type)->with('files')->paginate(20)
        ]);
    }


    public function store(StorePost $request, Post $post)
    {
        
    }

    public function view(string $id, Request $request)
    {
        $post = Post::withRelation()->where('_id', $id)->firstOrFail();

        $this->customer->createLog([
            'content' => "Đã xem tin: $post->title",
            'link'    => $request->fullUrl()
        ]);

        return view('customer.components.post-content', [
            'post' => $post,
            'customer' => $this->customer,
        ]);
    }

    /**
     * default select data from post collection
     *
     * @return \Jenssegers\Mongodb\Eloquent\Builder
     */
    private function defaultPost(string $type)
    {
        $categories = Category::flat($this->accessCategories($type))
            ->map(function ($cat) {
                return $cat->id;
            });

        $post = Post::withRelation()
            ->published()
            ->where('type', $type)
            ->whereNotIn('_id', $this->customer->post_blacklist_ids ?? [])
            ->filter([
                'categories' => $categories,
                'provinces'  => $this->access->provinces($type)
            ])->filter(request());

        if (request('order') === 'newest' || empty(request('query'))) {
            $post->newest();
        } elseif(! empty(request('query'))) {
            $post->OrderByScore();
        }

        return $post;
    }
}
