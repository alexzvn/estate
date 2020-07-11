<?php

namespace App\Http\Controllers\Manager\Post;

use Illuminate\Http\Request;
use App\Http\Controllers\Manager\Controller;
use App\Models\Category;
use App\Models\Location\VietNam\Province;

class PostController extends Controller
{
    public function create(Request $request)
    {
        return view('dashboard.post.create', [
            'provinces' => Province::with('districts')->active()->get(['name']),
            'categories' => Category::with('children')->parentOnly()->get(['name']),
        ]);
    }
}
