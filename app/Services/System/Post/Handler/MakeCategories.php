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
        $attr->categories ??= [];

        $this->checkCategoriesAttribute($attr);

        $categories = $this->converterCategoryToIds($attr->categories_ids ?? []);

        $attr->categories = $this->converterCategoryToIds(
            array_merge($attr->categories, $categories)
        );

        return $next($attr);
    }

    protected function converterCategoryToIds($categories)
    {
        return collect($categories)->map(function ($category)
        {
            return $category->_id;
        })->toArray();
    }

    /**
     * Converter vategory to instances
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
