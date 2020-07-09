<?php

namespace App\Http\Controllers\Manager\Role;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Manager\Controller;

class RoleController extends Controller
{
    /**
     * List all roles
     *
     * @return
     */
    public function index(Request $request)
    {
        $this->authorize('role.view');

        return view('dashboard.role.list', ['roles' => Role::all()]);
    }
}
