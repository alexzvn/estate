<?php

namespace App\Http\Controllers\Manager\Post;

use App\Repository\File;
use App\Models\Whitelist;
use Illuminate\Support\Str;
use App\Repository\Category;
use Illuminate\Http\Request;
use App\Repository\Location\Province;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Manager\Controller;
use Intervention\Image\Image;

class PostController extends Controller
{
    protected function shareCategoriesProvinces()
    {
        [$categories, $provinces] = Cache::remember(
            'dashboard.share.default',
            now()->addSeconds(360),
            function () {
                return [
                    Category::parentOnly()->with('children')->get(),
                    Province::active()->with('districts')->get(),
                ];
            }
        );

        view()->share(compact('categories', 'provinces'));
    }

    protected function syncUploadFiles($post, Request $request)
    {
        $ids = collect($request->image_ids ?? []);

        foreach ($request->file('images') ?? [] as $file) {
            $uploaded = File::create([
                'name' => $file->getClientOriginalName(),
                'path' => $this->storeImage(image($file))
            ]);

            $ids->push($uploaded->id);
        }

        if ($ids->count()) {
            $post->files()->sync($ids->toArray());
        }
    }

    protected function storeImage(Image $image)
    {
        if ($image->getHeight() > 1080) {
            $image->heighten(1080);
        }

        $image->encode('jpg', 75);

        $path = '/media/' . Str::uuid() . '.jpg';

        return tap($path, function ($path) use ($image)
        {
            $image->orientate()->save(public_path($path));
        });
    }
}
