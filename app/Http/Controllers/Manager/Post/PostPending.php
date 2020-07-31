<?php

namespace App\Http\Controllers\Manager\Post;

use App\Repository\Post;
use Illuminate\Http\Request;

class PostPending extends PostController
{
    public function index(Request $request)
    {
        $this->authorize('manager.post.view');

        $post = Post::with(['metas.province', 'categories', 'user'])
            ->latest()
            ->pending()
            ->filterRequest($request)
            ->whereHas('user', function ($q) {
                $q->whereHas('roles', function ($q)
                {
                    $q->where('customer', true);
                });
            });

        return view('dashboard.post.list', [
            'posts' => $post->paginate(30)
        ]);
    }
}
