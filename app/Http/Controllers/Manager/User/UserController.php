<?php

namespace App\Http\Controllers\Manager\User;

use App\Repository\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Manager\Controller;
use App\Http\Requests\Manager\User\UpdateUser;
use App\Repository\PermissionGroup;
use App\Repository\Role;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('manager.user.view');

        return view('dashboard.user.index', [
            'users' => User::filter($request)->latest()->paginate(20)
        ]);
    }

    public function create()
    {
        $this->authorize('manager.user.create');

        return view('dashboard.user.create');
    }

    public function edit(string $id, User $user)
    {
        $this->authorize('manager.user.view');

        return view('dashboard.user.view', [
            'user' => $user->with(['roles', 'permissions'])->findOrFail($id),
            'roles' => Role::all(),
        ]);
    }

    public function store()
    {
        
    }

    public function update(UpdateUser $request)
    {
        $attr = $request->all();
        unset($attr['password']);

        $user = $request->updateUser;

        $user->fill($attr)->fill([
            'phone' => str_replace('.', '', $request->phone)
        ])->save();

        $user->roles()->sync($request->roles ?? []);

        if (! empty($request->password)) {
            $user->forceFill([
                'password' => Hash::make($request->password)
            ])->save();
        }

        return back()->with('success', 'Cập nhật thành công');
    }

    public function delete()
    {
        # code...
    }
}
