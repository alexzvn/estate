<?php

namespace App\Http\Controllers\Manager\Post;

use App\Repository\Post;

class PostOnlineController extends PostController
{
    public function view(string $id, Post $post)
    {
        $this->authorize('manager.post.view');

        return $post->with(['categories', 'metas', 'user'])
            ->findOrFail($id)
            ->loadMeta();
    }
}
