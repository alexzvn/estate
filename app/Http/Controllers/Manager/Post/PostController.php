<?php

namespace App\Http\Controllers\Manager\Post;

use App\Repository\File;
use App\Models\Whitelist;
use App\Repository\Category;
use Illuminate\Http\Request;
use App\Repository\Location\Province;
use App\Http\Controllers\Manager\Controller;

class PostController extends Controller
{
    protected function shareCategoriesProvinces()
    {
        view()->share('categories', Category::parentOnly()->with('children')->get());
        view()->share('provinces', Province::active()->with('districts')->get());
        view()->share('whitelist', Whitelist::all());
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

    public function deleteMany(Request $request)
    {
        Post::whereIn('_id', $request->ids ?? [])->get()->each(function ($post)
        {
            $post->delete();
        });

        return back()->with('success', 'Đã xóa các mục yêu cầu');
    }

    public function reverseMany(Request $request)
    {
        Post::whereIn('_id', $request->ids ?? [])->get()->each(function ($post)
        {
            $post->forceFill(['publish_at' => now(), 'reverser' => true])->save();
        });

        return back()->with('success', 'Đã đảo các tin đã chọn');
    }
}
