<?php

namespace App\Http\Controllers\Customer\Post;

use App\Http\Controllers\Controller;
use App\Repository\Category;
use App\Repository\Location\Province;
use App\Services\Customer\Customer;

class BaseController extends Controller
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
     * @var \App\Services\Customer\Access\AccessManager
     */
    protected $access;

    public function __construct() {

        $this->middleware(function ($request, $next)
        {
            $this->customer = new Customer($request->user());
            $this->access   = $this->customer->access();

            view()->share('categories', $this->accessCategories());
            view()->share('provinces',  $this->accessProvinces());

            return $next($request);
        });
    }

    public function accessProvinces()
    {
        return Province::with('districts')
            ->findMany($this->access->getProvinces());
    }

    public function accessCategories()
    {
        return Category::with('children')
            ->findMany($this->access->getCategories());
    }
}
