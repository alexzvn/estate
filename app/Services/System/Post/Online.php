<?php

namespace App\Services\System\Post;

use App\Enums\PostType;
use App\Models\Post as ModelsPost;
use App\Repository\Post;

class Online extends Post
{
    use PostService;

    public const TYPE = PostType::Online;

    public function __construct(ModelsPost $post) {
        $this->model = $post->where('type', self::TYPE);
    }
}
