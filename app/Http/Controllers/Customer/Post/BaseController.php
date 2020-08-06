<?php

namespace App\Http\Controllers\Customer\Post;

use App\Http\Controllers\Controller;
use App\Repository\Category;
use App\Repository\Location\Province;
use App\Services\Customer\Customer;

abstract class BaseController extends Controller
{
    /**
     * Customer service
     *
     * @var \App\Services\Customer\Customer
     */
    protected $customer;

    /**
     * Customer access
     *
     * @var \App\Services\Customer\Access\AccessPost
     */
    protected $access;

    public function __construct() {

        $this->middleware(function ($request, $next)
        {
            $this->customer = new Customer($request->user());
            $this->access   = $this->customer->access()->post();

            return $next($request);
        });
    }

    protected function shareView(string $type)
    {
        view()->share('categories', $this->accessCategories($type));
        view()->share('provinces',  $this->accessProvinces($type));
    }

    protected function accessProvinces(string $type = null)
    {
        return Province::with('districts')
            ->findMany($this->access->provinces($type));
    }

    protected function accessCategories(string $type = null)
    {
        return Category::with('children')
            ->findMany($this->access->categories($type));
    }
}
