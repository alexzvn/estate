<?php

namespace App\Http\Controllers\Manager\Post;

use App\Repository\File;
use App\Models\Whitelist;
use App\Repository\Category;
use Illuminate\Http\Request;
use App\Repository\Location\Province;
use App\Http\Controllers\Manager\Controller;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{
    protected function shareCategoriesProvinces()
    {
        [$categories, $provinces, $whitelist] = Cache::remember(
            'dashboard.share.default',
            now()->addSeconds(360),
            function () {
                return [
                    Category::parentOnly()->with('children')->get(),
                    Province::active()->with('districts')->get(),
                ];
            }
        );

        view()->share(compact('categories', 'provinces', 'whitelist'));
    }

    protected function syncUploadFiles($post, Request $request)
    {
        $ids = collect($request->image_ids ?? []);

        if (! $request->hasFile('images')) {
            return;
        }

        foreach ($request->file('images') as $file) {
            $uploaded = File::create([
                'name' => $file->getFilename(),
                'path' => $file->store('media/images', 'public')
            ]);

            $ids->push($uploaded->id);
        }

        if ($ids->count()) {
            $post->files()->sync($ids->toArray());
        }
    }
}
