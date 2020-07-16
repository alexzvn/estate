<?php

namespace App\Observers;

use App\Models\Category;
use App\Models\Permission;

class CategoryObserver
{
    /**
     * Handle the category "created" event.
     *
     * @param  \App\App\Models\Category  $category
     * @return void
     */
    public function created(Category $category)
    {
        Permission::create([
            'name' => "post.category.access.$category->id",
            'display_name' => $category->name
        ]);
    }

    /**
     * Handle the category "updated" event.
     *
     * @param  \App\App\Models\Category  $category
     * @return void
     */
    public function updated(Category $category)
    {
        Permission::findByName("post.category.access.$category->id")
            ->fill(['display_name' => $category->name])->save();
    }

    /**
     * Handle the category "deleted" event.
     *
     * @param  \App\App\Models\Category  $category
     * @return void
     */
    public function deleted(Category $category)
    {
        Permission::findByName("post.category.access.$category->id")->delete();
    }
}
