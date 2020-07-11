<?php

namespace App\Http\Controllers\Manager\Category;

use Illuminate\Http\Request;
use App\Http\Controllers\Manager\Controller;
use App\Http\Requests\Manager\Category\StoreRequest;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        return view('dashboard.category.index', [
            'categories' => Category::parentOnly()->with('children')->get()
        ]);
    }

    public function store(StoreRequest $request)
    {
        $this->authorize('manager.category.create');

        $category = Category::create($request->all());

        if ($parent = Category::find($request->parent)) {
            $parent->children()->save($category);
        }

        return $this->index();
    }
}
