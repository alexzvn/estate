<?php

namespace App\Http\Controllers\Manager\Censorship;

use App\Enums\PostMeta;
use App\Repository\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Manager\Controller;
use App\Http\Requests\Manager\Censorship\Blacklist\AddBlacklist;
use App\Http\Requests\Manager\Censorship\Blacklist\AddWhitelist;
use App\Models\Whitelist;
use App\Repository\Blacklist;
use App\Repository\Location\Province;

class PostController extends Controller
{
    public function index(Post $post, Request $request)
    {
        $this->authorize('manager.post.view');

        $post = $post->with(['metas.province', 'metas.district', 'categories'])->filterRequest($request);

        $post = $this->filterTracking($post, $request)
            ->published()
            ->whereHas('metas', function ($builder)
            {
                $builder->whereHas('trackingPost', function ($builder)
                {
                    $builder->withoutWhitelist();
                });
            });

        return view('dashboard.censorship.index', [
            'posts' => $post->paginate(40),
            'provinces' => Province::with('districts')->active()->get()
        ]);
    }

    public function filterTracking($post, Request $request)
    {
        return $post->whereHas('metas', function ($builder) use ($request)
        {
            $builder->whereHas('trackingPost', function ($builder) use ($request)
            {
                if ($request->categories_unique) {
                    $builder->where('categories_unique', '>', (int) $request->categories_unique);
                }

                if ($request->district_unique) {
                    $builder->where('district_unique', '>', (int) $request->district_unique);
                }

                if ($request->seen) {
                    $builder->where('seen', '>', $request->seen ? (int) $request->seen : 1);
                }
            });
        });
    }

    public function addToBlacklist(Blacklist $blacklist, AddBlacklist $request)
    {
        $this->authorize('blacklist.phone.create');

        $blacklist->findByPhoneOrCreate($request->phone);

        return back()->with('success', "Đã chặn số $request->phone");
    }

    public function addToWhitelist(Whitelist $whitelist, AddWhitelist $request)
    {
        $this->authorize('blacklist.phone.create');

        $whitelist->findByPhoneOrCreate($request->phone);

        return back()->with('success', "Đã chặn số $request->phone");
    }
}
