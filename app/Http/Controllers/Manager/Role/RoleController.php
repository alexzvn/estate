<?php

namespace App\Http\Controllers\Manager\Role;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

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
