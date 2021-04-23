<?php

namespace App\Http\Controllers\Customer\Post;

use App\Repository\Category;
use App\Repository\Location\Province;
use App\Repository\Post;

class CustomerPost extends BaseController
{
    public function __construct() {
        view()->share('categories', Category::parentOnly()->with('children')->get());
        view()->share('provinces', Province::active()->with('districts')->get());

        parent::__construct();
    }

    public function saved()
    {
        $post = $this->customer->savePosts()->with([
            'province',
            'district',
            'categories'
        ])->filter(request())->latest()->paginate(20);

        $this->customer->createLog([
            'content' => 'Truy cập trang tin đã lưu'
        ]);

        return view('customer.post.owner', [
            'posts' => $post
        ]);
    }

    public function posted()
    {
        $post = $this->customer->posts()->with([
            'province',
            'district',
            'categories'
        ])->filter(request())->paginate(20);

        $this->customer->createLog([
            'content' => 'Truy cập trang tin đã đăng',
        ]);

        return view('customer.post.owner', [
            'posts' => $post
        ]);
    }

    public function blacklist()
    {
        $post = $this->customer->blacklistPosts([
            'province',
            'district',
            'categories'
        ])->filter(request())->latest()->paginate(20);

        $this->customer->createLog([
            'content' => 'Truy cập trang tin đã xóa',
        ]);

        return view('customer.post.owner', [
            'posts' => $post
        ]);
    }
}
