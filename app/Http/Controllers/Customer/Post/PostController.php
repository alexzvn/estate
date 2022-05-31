<?php

namespace App\Http\Controllers\Customer\Post;

use App\Enums\PostStatus;
use App\Enums\PostType;
use App\Repository\Post;
use App\Repository\Category;
use App\Http\Controllers\Customer\Post\BaseController;
use App\Http\Requests\Customer\Post\StorePost;
use App\Models\ScoutFilter\PostFilter;
use App\Repository\Meta;
use Illuminate\Http\Request;

class PostController extends BaseController
{
    public function fee(Request $request)
    {
        $type = PostType::PostFee;

        $this->customer->createLog([
            'content' => 'Đã truy cập '. PostType::getDescription($type),
            'link'    => $request->fullUrl()
        ]);

        $this->shareView($type);

        return view('customer.post.fee', [
            'canAccess' => $this->access->canAccess($type),
            'posts' => $this->defaultPost($type)->paginate(40)
        ]);
    }

    public function online(Request $request)
    {
        $type = PostType::Online;

        $this->customer->createLog([
            'content' => 'Đã truy cập '. PostType::getDescription($type),
            'link'    => $request->fullUrl()
        ]);

        $this->shareView($type);

        return view('customer.post.online', [
            'canAccess' => $this->access->canAccess($type),
            'posts' => $this->defaultPost($type)->paginate(40)
        ]);
    }

    public function market(Request $request)
    {
        $type = PostType::PostMarket;

        $this->customer->createLog([
            'content' => 'Đã truy cập '. PostType::getDescription($type),
            'link'    => $request->fullUrl()
        ]);

        $this->shareView($type);

        return view('customer.post.market', [
            'canAccess' => $this->access->canAccess($type),
            'posts' => $this->defaultPost($type)->with('files')->paginate(40)
        ]);
    }


    public function store(StorePost $request, Post $post)
    {
        
    }

    public function view(string $id, Request $request)
    {
        $post = Post::withRelation()->where('id', $id)->firstOrFail();

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
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function defaultPost(int $type)
    {
        $categories = Category::flat($this->accessCategories($type))
            ->map(fn($cat) => $cat->id);

        // if (request('query')) {
            return $this->performSearch($type, $categories);
        // }

        return Post::newest()
            ->with(['categories', 'province', 'district'])
            ->whereType($type)
            ->published()
            ->whereNotIn('id', $this->getBlacklistIds())
            ->whereIn('province_id', $this->access->provinces($type))
            ->filter(['categories' => $categories])
            ->filter(request());
    }

    protected function performSearch($type, $categories)
    {
        $query = str_replace(['\\', '/'], ['\\\\', '\\/'], request('query', '*'));

        $post = Post::search($query)
            ->with(['categories', 'province', 'district'])
            ->whereNotIn('id', $this->getBlacklistIds())
            ->where('status', PostStatus::Published)
            ->where('type', $type)
            ->whereIn('category_id', $categories->toArray())
            ->whereIn('province_id', $this->access->provinces($type));

        if (! request('order')) {
            $post->orderBy('publish_at', 'desc');
        }

        PostFilter::filter($post, request());

        return $post;
    }

    /**
     * Undocumented function
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getBlacklistIds()
    {
        return user()->blacklistPosts()->get(['id'])
            ->keyBy('id')->keys()->toArray();
    }
}
