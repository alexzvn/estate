<?php

namespace App\Http\Controllers\Customer;

use App\Enums\PostType;
use App\Http\Controllers\Controller;
use App\Repository\Category;
use App\Repository\Location\Province;
use App\Repository\Post;
use App\Services\Customer\Customer;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Customer service
     *
     * @var \App\Services\Customer\Customer
     */
    protected $customer;

    /**
     * Customer access
     *
     * @var \App\Services\Customer\Access\AccessManager
     */
    protected $access;

    public function __construct() {

        $this->middleware(function ($request, $next)
        {
            $this->customer = new Customer($request->user());
            $this->access   = $this->customer->access();

            view()->share('categories', $this->accessCategories());
            view()->share('provinces',  $this->accessProvinces());

            return $next($request);
        });
    }

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
            ->filterRequest([
                'categories' => $categories,
                'provinces'  => $access->getProvinces()
            ]);
    }

    public function accessProvinces()
    {
        return Province::with('districts')
            ->findMany($this->access->getProvinces());
    }

    public function accessCategories()
    {
        return Category::with('children')
            ->findMany($this->access->getCategories());
    }
}
