<?php

namespace App\Services\System\Post\Handler;

use App\Contracts\Handler;
use Mews\Purifier\Facades\Purifier;

class CleanScript implements Handler
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
        $attr->content = Purifier::clean($attr->content ?? '');

        return $attr;
    }
}
