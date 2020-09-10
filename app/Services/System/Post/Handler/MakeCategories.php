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

        if (isset($attr->categories) && ! is_array($attr->categories)) {
            throw new \Exception("attr categories must be array of instance " . Category::class, 1);
        }

        if (isset($attr->category_ids) && is_array($attr->category_ids) ) {
            $attr->categories = [...$attr->categories, ...Category::findMany($attr->category_ids)];
        }

        $attr->categories = collect($attr->categories)->unique('_id');

        return $attr;
    }
}
