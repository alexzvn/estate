<?php

namespace App\Http\Controllers\Manager\Post;

use App\Repository\Post;
use App\Enums\PostStatus;
use App\Enums\PostType;
use App\Http\Requests\Manager\Post\ClonePost;
use App\Services\System\Post\PostService;

class OnlineController extends PostController
{
    public function view(string $id, Post $post)
    {
        $this->authorize('manager.post.view');

        return $post->with(['categories', 'user'])
            ->findOrFail($id);
    }

    public function cloneSaveOrigin(string $id, ClonePost $request)
    {
        $post = Post::findOrFail($id)->replicate();

        $request->user()->posts()->save($post);

        if ($request->status == PostStatus::Published) {
            $post->publish_at = now(); $post->save();
        }

        return response([
            'success' => true,
            'data' => 'Đã duyệt lưu gốc'
        ]);
    }

    public function cloneDeleteOrigin(string $id, ClonePost $request)
    {
        $post = Post::findOrFail($id);

        PostService::update($post, $request->all());

        $post->fill(['type' => PostType::PostFee])->save();

        return response([
            'success' => true,
            'data' => 'Đã duyệt xóa gốc',
        ]);
    }
}
