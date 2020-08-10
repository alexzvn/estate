<?php

namespace App\Http\Controllers\Manager\Censorship;

use App\Enums\PostMeta;
use App\Http\Controllers\Manager\Censorship\Support\CensorshipCollection;
use Illuminate\Http\Request;
use App\Http\Controllers\Manager\Controller;
use App\Repository\Meta;
use Illuminate\Pagination\LengthAwarePaginator;

class PostController extends Controller
{
    public function index(Meta $meta)
    {
        $this->authorize('manager.post.view');

        $meta = Meta::with(['post.categories', 'post.metas.province','post.metas.district'])
                ->latest()
                ->where('name', PostMeta::Phone)
                ->limit(2000)
                ->get()->groupBy('value');

        $meta = $meta->filter(function ($value)
        {
            return count($value) > 1;
        });

        $meta = new CensorshipCollection($meta);
        $meta = new LengthAwarePaginator($meta, $meta->count(), 10);

        return view('dashboard.censorship.index', [
            'metas' => $meta
        ]);
    }
}
