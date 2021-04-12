<?php

namespace App\Services\System\Post\Handler;

use App\Contracts\Handler;
use App\Models\Category;

class MakeCategories implements Handler
{
    /**
     * Clean javascript out of html content
     *
     * @param object $value
     * @param Closure $next
     * @return mixed
     */
    public function handle($attr, \Closure $next)
    {
        $this->checkCategoriesAttribute($attr);

        $attr->categories ??= [];

        $this->removeNullCategories($attr->categories);

        $categories = $this->converterCategoryToIds($attr->categories_ids ?? []);

        $attr->categories = $this->converterCategoryToIds(
            array_merge($attr->categories, $categories)
        );

        $attr->categories = [...$attr->categories, ...($attr->category_ids ?? [])];

        return $next($attr);
    }

    protected function removeNullCategories(array &$category)
    {
        for ($i=0; $i < count($category); $i++) { 
            if (empty($category[$i])) {
                unset($category[$i]);
            }
        }
    }

    protected function converterCategoryToIds($categories)
    {
        return collect($categories)->map(function ($category) {
            return $category->id;
        })->toArray();
    }

    /**
     * Converter category to instances
     *
     * @param string|array $ids
     * @return array
     */
    protected function converterCategoryToInstances($ids)
    {
        if (is_array($ids)) {
            return Category::findMany($ids);
        }

        if ($category = Category::find($ids)) {
            return [$category];
        }

        return [];
    }

    protected function checkCategoriesAttribute($attr)
    {
        if (isset($attr->categories) && ! is_array($attr->categories)) {
            throw new \Exception("attr categories must be array of instance " . Category::class, 1);
        }
    }
}
