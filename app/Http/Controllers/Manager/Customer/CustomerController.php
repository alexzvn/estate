<?php

namespace App\Http\Controllers\Manager\Customer;

use App\Repository\Plan;
use App\Repository\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Manager\Controller;
use App\Http\Requests\Manager\Customer\StoreCustomer;
use App\Http\Requests\Manager\Customer\UpdateCustomer;
use App\Http\Requests\Manager\Customer\Order\StoreOrder;
use App\Models\Order;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('manager.customer.view');

        return view('dashboard.customer.index', [
            'users' => User::with('orders')->filterRequest($request)->onlyCustomer()->paginate(20)
        ]);
    }

    public function view(string $id, User $user)
    {
        $this->authorize('manager.customer.view');

        return view('dashboard.customer.view', [
            'plans' => Plan::all(),
            'user' => $user->with(['roles', 'permissions'])->onlyCustomer()->findOrFail($id),
        ]);
    }

    public function create()
    {
        $this->authorize('manager.customer.create');

        return view('dashboard.customer.create');
    }

    public function store(StoreCustomer $request, User $user)
    {
        $user->fill($request->all());

        $user->fill([
            'phone' => str_replace('.', '', $request->phone),
            'password' => Hash::make($request->password)
        ])->save();

        return redirect(route('manager.customer.view', ['id' => $user->id]))
            ->with('success', 'Tạo mới thành công');
    }

    public function update(UpdateCustomer $request)
    {
        $user = $request->updateUser;
        $attr = $request->all();

        unset($attr['password']);

        $user->fill($attr)->fill([
            'phone' => str_replace('.', '', $request->phone)
        ])->save();

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
        $this->authorize('manager.user.verify.phone');

        $user = $user->findOrFail($id);

        if (! $user->hasVerifiedPhone()) {
            $user->markPhoneAsVerified();
        }

        return back()->with('success', 'Xác thực số điện thoại thành công');
    }

    public function unverifiedPhone(string $id, User $user)
    {
        $this->authorize('manager.user.verify.phone');

        $user = $user->findOrFail($id);

        if ($user->hasVerifiedPhone()) {
            $user->markPhoneAsNotVerified();
        }

        return back()->with('success', 'Bỏ xác thực thành công');
    }

    public function storeOrder(string $id, StoreOrder $request)
    {
        $order = User::findOrFail($id)->orders()->create([
            'status' => Order::PENDING,
        ]);

        $order->plans()->sync($request->plans ?? []);

        return redirect(route('manager.order.view', ['id' => $order->id]));
    }
}
