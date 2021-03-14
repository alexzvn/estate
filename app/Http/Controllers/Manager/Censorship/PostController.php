<?php

namespace App\Http\Controllers\Manager\Censorship;

use Illuminate\Http\Request;
use App\Http\Controllers\Manager\Controller;
use App\Http\Requests\Manager\Censorship\Blacklist\AddBlacklist;
use App\Http\Requests\Manager\Censorship\Blacklist\AddWhitelist;
use App\Models\Whitelist;
use App\Repository\Blacklist;
use App\Repository\Location\Province;
use App\Services\System\Post\Online;

class PostController extends Controller
{
    public function index(Online $post, Request $request)
    {
        $this->authorize('manager.censorship.view');

        $post = $post->with(['province', 'district', 'categories'])->filter($request);

        $post = $this->filterTracking($post, $request)
            ->published()
            ->newest()
            ->withoutWhitelist();

        return view('dashboard.censorship.index', [
            'posts' => $post->simplePaginate(40),
            'provinces' => Province::with('districts')->active()->get()
        ]);
    }

    public function filterTracking($post, Request $request)
    {
        return $post->whereHas('tracking', function ($builder) use ($request)
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
    }

    public function addToBlacklist(Blacklist $blacklist, AddBlacklist $request)
    {
        $this->authorize('blacklist.phone.create');

        $this->makeListPhone($request->phone)
            ->each(function ($phone) use ($blacklist)
            {
                $blacklist = $blacklist->findByPhoneOrCreate($phone);

                $blacklist->forceFill(['user_id' => user()->id])->save();
            });

        return back()->with('success', "Đã chặn số $request->phone");
    }

    public function addToWhitelist(Whitelist $whitelist, AddWhitelist $request)
    {
        $this->authorize('whitelist.phone.create');

        $this->makeListPhone($request->phone)
            ->each(function ($phone) use ($whitelist)
            {
                $whitelist = $whitelist->findByPhoneOrCreate($phone);

                $whitelist->forceFill(['user_id' => user()->id])->save();
            });

        return back()->with('success', "Đã thêm số $request->phone vào danh sách trắng");
    }

    /**
     * "0123, 0124" -> [0123, 0124]
     *
     * @param string $phone
     * @return \Illuminate\Support\Collection|string[]|array
     */
    private function makeListPhone(string $phone)
    {
        return collect(explode(',', $phone))
            ->map(function ($phone) {
                return trim($phone);
            });
    }
}
