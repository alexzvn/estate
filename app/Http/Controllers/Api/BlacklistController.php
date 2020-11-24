<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blacklist;
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
            $data[] = [
                'phone' => $phone,
                'source' => 'api'
            ];
        }

        if (isset($data) && count($data) > 0) {

            Post::lockByPhone($blacklist);

            return response([
                'success' => Blacklist::insert($data),
                'phoneToAdd' => count($blacklist),
            ]);
        }

        return response([
            'success' => true,
            'phoneToAdd' => 0
        ]);
    }

    protected function filterDuplicated(Collection $list)
    {
        $duplicated = Blacklist::whereIn('phone', $list->toArray())
            ->get()
            ->reduce(function ($carry, $phone)
            {
                $carry[] = $phone->phone;
                return $carry;
            }, []);

        return $list->filter(function ($phone) use ($duplicated)
        {
            return ! in_array($phone, $duplicated);
        });
    }

    public function createPhoneCollection(Request $request) : Collection
    {
        $listPhone = json_decode($request->getContent());

        return array_reduce($listPhone, function (Collection $carry, $item)
        {
            return $carry->push($item->phoneNumber);
        }, collect());
    }
}