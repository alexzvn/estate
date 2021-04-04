<?php

namespace App\Http\Controllers\Manager\Keyword;

use App\Models\Keyword;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Manager\Controller;
use App\Models\Category;
use App\Models\Location\Province;
use App\Models\Whitelist;

class KeywordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dashboard.keyword.index', [
            'keywords' => Keyword::latest()->paginate(40)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'key' => 'required|string|unique:keywords,key',
            'linear' => 'nullable|boolean'
        ], [], [
            'key' => 'từ khóa'
        ]);

        Keyword::create([
            'key' => $request->key,
            'posts' => collect(),
            'linear' => $request->boolean('linear')
        ]);

        return back()->with('success', 'Đã tạo từ mới');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Keyword  $keyword
     * @return \Illuminate\Http\Response
     */
    public function view(Keyword $keyword)
    {
        return view('dashboard.keyword.view', [
            'keyword'    => $keyword,
            'categories' => Category::parentOnly()->with('children')->get(),
            'provinces'  => Province::with('districts')->get(),
            'whitelist'  => Whitelist::all(),
            'keywords'   => Keyword::all(),
            'posts'      => Post::whereIn('_id', $keyword->posts)->latest()
                ->with(['categories', 'province', 'district', 'tracking'])->paginate(40)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Keyword  $keyword
     * @return \Illuminate\Http\Response
     */
    public function delete(Keyword $keyword)
    {
        $keyword->delete();

        return back()->with('success', 'Đã xóa thành công');
    }
}
