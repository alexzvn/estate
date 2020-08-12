<?php

namespace App\Http\Controllers\Manager\Censorship;

use App\Enums\PostMeta;
use App\Repository\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Manager\Controller;
use App\Repository\Location\Province;

class PostController extends Controller
{
    public function index(Post $post, Request $request)
    {
        $this->authorize('manager.post.view');

        $post = $post->with(['metas.province', 'metas.district', 'categories'])->filterRequest($request);

        $post->whereHas('metas', function ($builder) use ($request)
        {
            $builder->whereHas('trackingPost', function ($builder) use ($request)
            {
                $builder->where('seen', '>=', 2);

                if ($request->phone_more_than_categories_3) {
                    $builder->where('categories_unique', '>=', 3);
                }

                if ($request->phone_more_than_district_3) {
                    $builder->where('district_unique', '>=', 3);
                }
            });
        });

        return view('dashboard.censorship.index', [
            'posts' => $post->paginate(40),
            'provinces' => Province::with('districts')->active()->get()
        ]);
    }
}
