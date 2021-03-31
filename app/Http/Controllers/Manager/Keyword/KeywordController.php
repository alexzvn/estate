<?php

namespace App\Http\Controllers\Manager\Keyword;

use App\Models\Keyword;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Manager\Controller;

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
            'keywords' => Keyword::latest()->paginate()
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
            'key' => 'required|string'
        ]);

        Keyword::create([
            'key' => $request->key,
            'posts' => collect()
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
            'keyword' => $keyword,
            'posts'   => Post::whereIn('_id', $keyword->posts)->paginate()
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
