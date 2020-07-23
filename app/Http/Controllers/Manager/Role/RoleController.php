<?php

namespace App\Http\Controllers\Manager\Role;

use App\Repository\Role;
use App\Http\Controllers\Manager\Controller;
use App\Http\Requests\Manager\Role\RoleStore;
use App\Http\Requests\Manager\Role\UpdateRole;
use App\Repository\Permission;
use App\Repository\PermissionGroup;

class RoleController extends Controller
{
    /**
     * List all roles
     *
     * @return
     */
    public function index()
    {
        $this->authorize('manager.role.view');

        return view('dashboard.role.list', ['roles' => Role::all()]);
    }

    public function create()
    {
        $this->authorize('manager.role.create');

        return view('dashboard.role.create', [
            'groups' => PermissionGroup::with('permissions')->get()
        ]);
    }

    public function view(string $id, Role $role)
    {
        $this->authorize('manager.role.view');

        return view('dashboard.role.view', [
            'role' => $role->with('permissions')->findOrFail($id),
            'groups' => PermissionGroup::with('permissions')->get()
        ]);
    }

    public function update(string $id, UpdateRole $request)
    {
        $role = Role::findOrFail($id)->fill($request->only('name'));

        if ($request->permissions) {
            $role->syncPermissions($this->permissionIdToName($request->permissions));
        }

        $role->forceFill(['customer' => (bool) $request->for_customer])->save();

        return back()->with('success', 'Cập nhật thành công');
    }

    public function store(RoleStore $request)
    {
        $role = Role::fill($request->only('name'))->forceFill([
            'customer' => (bool) $request->for_customer,
            'guard_name' => 'web'
        ]);

        $role->save();

        if ($request->permissions) {
            $role->syncPermissions($this->permissionIdToName($request->permissions));
        }

        return redirect(route('manager.role'))
            ->with('success', 'Tạo vai trò mới thành công');
    }

    public function delete(string $id, Role $role)
    {
        $this->authorize('manager.role.delete');

        $role->findOrFail($id)->delete();

        return redirect(route('manager.role'))->with('success', 'Xóa thành công');
    }

    private function permissionIdToName(array $ids = [])
    {
        return Permission::findMany($ids)->map(function ($perm)
        {
            return $perm->name;
        });
    }
}
