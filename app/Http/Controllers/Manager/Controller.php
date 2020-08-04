<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller as BaseController;
use App\Repository\Role;

class Controller extends BaseController
{
    public function __construct() {

        $roles = Role::all();

        view()->share('roles', $roles);
        view()->share('customerRoles', $roles->filter(function ($role) {
            return $role->customer;
        }));
    }

    public function InfoPost(){

        return view("dashboard.test");
    }
}
