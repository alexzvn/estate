<?php

namespace App\Http\Controllers\Manager\Post;

use App\Repository\Post;
use App\Enums\PostStatus;
use App\Enums\PostType;
use App\Repository\Category;
use Illuminate\Http\Request;
use Mews\Purifier\Facades\Purifier;
use App\Http\Requests\Manager\Post\ClonePost;

class PostOnlineController extends PostController
{
    public function view(string $id, Post $post)
    {
        $this->authorize('manager.post.view');

        return $post->with(['categories', 'metas', 'user'])
            ->findOrFail($id)
            ->loadMeta();
    }

    public function cloneSaveOrigin(ClonePost $request)
    {
        $post = Post::create(
            array_merge($request->all(), [
                'content' => Purifier::clean($request->post_content),
                'type' => PostType::PostFee,
            ])
        );

        $post->categories()->save(Category::find($request->category));

        $this->makeSaveMeta($post, $request);

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
        $post = $this->update($id, $request);

        $post->fill(['type' => PostType::PostFee])->save();

        return response([
            'success' => true,
            'data' => 'Đã duyệt xóa gốc',
        ]);
    }
}
