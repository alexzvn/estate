<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller as BaseController;
use App\Models\Role;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    public function __construct() {
        view()->share('roles', Role::all());
    }
}
