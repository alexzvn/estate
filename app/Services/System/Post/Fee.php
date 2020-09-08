<?php

namespace App\Services\System\Post;

use App\Enums\PostType;
use App\Models\Post as ModelsPost;
use App\Repository\Post;

class Fee extends Post
{
    use PostService;

    public function __construct(ModelsPost $post) {
        $this->model = $post->where('type', PostType::PostFee);
    }
}
