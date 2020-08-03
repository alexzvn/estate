<?php

namespace App\Http\Controllers\Customer\Post;

use App\Repository\Post;

class ActionController extends BaseController
{
    public function blacklist(string $id, Post $post)
    {
        if (! ($post = $post->find($id))) {
            return response('Không tìm thấy tin này', 404);
        }

        if ($this->shouldNotBlacklist($post)) {
            $this->customer->blacklistPosts()->detach($post->id);

            return response('Đã khôi phục tin này');
        }

        $this->customer->blacklistPosts()->attach($post->id);

        return response('Đã xóa tin này');
    }

    public function save(string $id, Post $post)
    {
        if (! ($post = $post->find($id))) {
            return response('Không tìm thấy tin này', 404);
        }

        if ($this->shouldNotSave($post)) {
            $this->customer->savePosts()->detach($post->id);

            return response('Đã bỏ lưu tin này');
        }

        $this->customer->savePosts()->attach($post->id);

        return response('Đã lưu tin này');
    }

    public function report(string $id, Post $post)
    {
        if (! ($post = $post->find($id))) {
            return response('Không tìm thấy tin này', 404);
        }
    }

    private function shouldNotSave($post)
    {
        return $this->customer->savePosts()->where('_id', $post->id)->exists();
    }

    private function shouldNotBlacklist($post)
    {
        return $this->customer->blacklistPosts()->where('_id', $post->id)->exists();
    }
}
