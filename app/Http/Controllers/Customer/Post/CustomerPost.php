<?php

namespace App\Http\Controllers\Customer\Post;

use App\Repository\Post;

class CustomerPost extends BaseController
{
    public function saved()
    {
        $post = $this->customer->savePosts()->with([
            'metas',
            'metas.province',
            'metas.district',
            'categories'
        ])->paginate(20);

        return view('customer.post.owner', [
            'posts' => $post
        ]);
    }

    public function posted()
    {
        $post = $this->customer->posts()->with([
            'metas',
            'metas.province',
            'metas.district',
            'categories'
        ])->paginate(20);

        return view('customer.post.owner', [
            'posts' => $post
        ]);
    }

    public function blacklist()
    {
        $post = $this->customer->blacklistPosts([
            'metas',
            'metas.province',
            'metas.district',
            'categories'
        ])->paginate(20);

        return view('customer.post.owner', [
            'posts' => $post
        ]);
    }
}
