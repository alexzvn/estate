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
        return view('dashboard.user.index', [
            'users' => User::filterRequest($request)->latest()->paginate(20)
        ]);
    }

    public function create()
    {
        return view('dashboard.user.create');
    }

    public function edit(string $id, User $user)
    {
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
        $user = $request->updateUser;

        $user->fill($request->all());
        $user->fill([
            'phone' => str_replace('.', '', $request->phone)
        ])->save();

        if ($request->roles) {
            $user->syncRoles(Role::findMany($request->roles));
        } else {
            $user->syncRoles([]);
        }

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

    public function verifyPhone(string $id, User $user)
    {
        /**
         * @var \App\Models\User
         */
        $user = $user->findOrFail($id);

        if (! $user->hasVerifiedPhone()) {
            $user->markPhoneAsVerified();
        }

        return back()->with('success', 'Xác thực số điện thoại thành công');
    }
}
