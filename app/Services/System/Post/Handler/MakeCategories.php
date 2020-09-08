<?php

namespace App\Services\System\Post\Handler;

use App\Contracts\Handler;
use Illuminate\Support\Collection;

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
        if (! ($attr->categories instanceof Collection)) {
            return $next($attr);
        }

        $attr->category_ids = $attr->categories->map(function ($category) {
            return $category->id;
        })->toArray();

        return $attr;
    }
}
