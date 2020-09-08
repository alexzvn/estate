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
        if (empty($attr->categories[0])) {
            return $next($attr);
        }

        if ($attr->categories[0] instanceof Category) {
            $attr->category_ids = [$attr->categories[0]->id];
        }

        return $attr;
    }
}
