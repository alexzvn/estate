<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blacklist;
use App\Models\Location\Province;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class BlacklistController extends Controller
{
    public function import(Request $request)
    {
        $blacklist = $this->filterDuplicated(
            $this->createPhoneCollection($request)
        );

        foreach ($blacklist as $phone) {
            Blacklist::forceCreate([
                'name'   => $phone->agencyName,
                'phone' => $phone->phoneNumber,
                'url'   => $phone->url,
                'province_id' => $this->getProvinceId($phone->region),
                'category' => $phone->category,
                'source' => 'api'
            ]);
        }

        Post::lockByPhone($blacklist->keyBy('phoneNumber')->keys());

        return response([
            'success' => true,
            'phoneToAdd' => count($blacklist)
        ]);
    }

    protected function filterDuplicated(Collection $list) : Collection
    {
        $listPhone = $list->map(fn($p) => $p->phoneNumber)->toArray();

        $duplicated = Blacklist::whereIn('phone', $listPhone)->get();

        return $list->filter(function ($phone) use ($duplicated)
        {
            return $duplicated->where('phone', $phone->phoneNumber)->isEmpty();
        });
    }

    protected function createPhoneCollection(Request $request) : Collection
    {
        return collect(
            json_decode($request->getContent())
        )->map(function ($phone) {
            $phone->region = trim(preg_replace('/^Tp/', '', $phone->region, 1));

            return $phone;
        });
    }

    protected function getProvinceId($name)
    {
        return Province::where('name', 'regexp', $name)->first()->id ?? null;
    }
}
