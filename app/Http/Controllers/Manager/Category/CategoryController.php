<?php

namespace App\Http\Controllers\Manager\Category;

use Illuminate\Http\Request;
use App\Http\Controllers\Manager\Controller;
use App\Http\Requests\Manager\Category\StoreRequest;
use App\Http\Requests\Manager\Category\UpdateCategory;
use App\Repository\Category;

class CategoryController extends Controller
{
    public function index()
    {
        return view('dashboard.category.index', [
            'categories' => Category::parentOnly()->with('children.children.children')->get()
        ]);
    }

    public function store(StoreRequest $request)
    {
        $this->authorize('manager.category.create');

        $category = Category::create($request->all());

        if ($parent = Category::find($request->parent)) {
            $parent->children()->save($category);
        }

        return back();
    }

    public function view(string $id, Category $category)
    {

        return view('dashboard.category.view', [
            'category'   => $category->findOrFail($id),
            'categories' => Category::parentOnly()->with('children.children')->get(),
        ]);
    }

    public function update(string $id, UpdateCategory $request)
    {
        $cat = Category::findOrFail($id);

        $cat->fill($request->all())->save();

        //save relationship if exists parent
        if (! empty($request->parent) && $parent = Category::find($request->parent)) {
            if ($parent->parent && $parent->parent_id == $cat->id) { // resolve unlimited recursive category parent
                $parent->parent_id = $parent->parent->parent->id ?? null; // set to one level more parent if exists
                $parent->save();
            }

            $parent->children()->save($cat);
        } else {
            $cat->forceFill(['parent_id' => null])->save();
        }

        return back()->with('success', 'Cập nhật thành công');
    }

    public function delete(string $id, Category $category)
    {
        $cat = $category->findOrFail($id);

        $cat->removeChildren();

        $cat->delete();

        return redirect(route('manager.category'))->with('success', 'Xóa thành công!');
    }
}
