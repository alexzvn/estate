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
            if (is_null($category[$i])) {
                unset($category[$i]);
            }
        }
    }

    protected function converterCategoryToIds($categories)
    {
        return collect($categories)->map(function ($category)
        {
            return $category->_id;
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
        if (is_string($ids)) {
            return [Category::find($ids)];
        }

        return Category::findMany($ids);
    }

    protected function checkCategoriesAttribute($attr)
    {
        if (isset($attr->categories) && ! is_array($attr->categories)) {
            throw new \Exception("attr categories must be array of instance " . Category::class, 1);
        }
    }
}
