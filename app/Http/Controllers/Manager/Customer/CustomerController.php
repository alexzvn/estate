<?php

namespace App\Http\Controllers\Manager\Customer;

use App\Repository\Plan;
use App\Repository\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Manager\Controller;
use App\Http\Requests\Manager\Customer\AssignCustomer;
use App\Http\Requests\Manager\Customer\StoreCustomer;
use App\Http\Requests\Manager\Customer\UpdateCustomer;
use App\Http\Requests\Manager\Customer\Order\StoreOrder;
use App\Models\Order;
use App\Models\User as ModelsUser;
use App\Repository\Role;
use App\Repository\Subscription;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('manager.customer.view');

        $users = User::with(['subscriptions', 'supporter'])
            ->filterRequest($request)
            ->onlyCustomer()
            ->latest()->paginate(20);

        return view('dashboard.customer.index', compact('users'));
    }

    public function view(string $id, User $user)
    {
        $this->authorize('manager.customer.view');

        $user = $user->with(['permissions', 'subscriptions.plan'])
                    ->onlyCustomer()->findOrFail($id);

        if ($user->supporter_id !== auth()->id() && request()->user()->cannot('manager.user.assign.customer')) {
            abort(403);
        }

        $staffs = Role::with('users')->staff()->get()
            ->reduce(function (Collection $carry, $role)
            {
                return $carry->push(...$role->users);
            }, collect());

        return view('dashboard.customer.view', [
            'plans' => Plan::all(),
            'staffs' => $staffs->unique('id'),
            'user' => $user,
        ]);
    }

    public function create()
    {
        $this->authorize('manager.customer.create');

        return view('dashboard.customer.create');
    }

    public function store(StoreCustomer $request, User $user)
    {
        $phone = str_replace('.', '', $request->phone);

        if (User::where('phone', $phone)->exists()) {
            return back()->withErrors(['phone' => 'Số điện thoại đã tồn tại trong hệ thống']);
        }

        $user->fill($request->all())->fill([
            'phone' => $phone,
            'password' => Hash::make($request->password)
        ]);

        if (empty($user->email)) {
            $user->email = $user->phone . '@' . parse_url(config('app.url'), PHP_URL_HOST);
        }

        $user->save();

        if ($request->user()->cannot('*')) {
            $this->assignCustomerToUser($user, Auth::id());
        }

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

        if ($request->user()->can('manager.user.assign.customer')) {
            $this->assignCustomer($user->id, app(AssignCustomer::class));
        }

        return back()->with('success', 'Cập nhật thành công');
    }

    public function assignCustomer(string $customerId, AssignCustomer $request)
    {
        $customer = User::whereHas('roles', function ($q)
        {
            $q->where('customer', true);
        })->findOrFail($customerId);

        $this->assignCustomerToUser($customer, empty($request->supporter_id) ? null : $request->supporter_id);

        return back()->with('success', 'Cập nhật thành công');
    }

    public function delete()
    {
        # code...
    }

    public function ban(string $id, User $user)
    {
        $this->authorize('manager.customer.ban');

        $user->findOrFail($id)->ban();

        return back()->with('success', 'Đã khóa tài khoản khách hàng');
    }

    public function pardon(string $id, User $user)
    {
        $this->authorize('manager.customer.pardon');

        $user->findOrFail($id)->pardon();

        return back()->with('success', 'Đã mở khóa tài khoản khách hàng');
    }

    public function verifyPhone(string $id, User $user)
    {
        $this->authorize('manager.customer.verify.phone');

        $user = $user->findOrFail($id);

        if (! $user->hasVerifiedPhone()) {
            $user->markPhoneAsVerified();
        }

        return back()->with('success', 'Xác thực số điện thoại thành công');
    }

    public function unverifiedPhone(string $id, User $user)
    {
        $this->authorize('manager.customer.verify.phone');

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
            'manual' => true,
        ]);

        $order->plans()->sync($request->plans ?? []);

        return redirect(route('manager.order.view', ['id' => $order->id]));
    }

    private function assignCustomerToUser($customer, $userId = null)
    {
        $customer->forceFill([
            'supporter_id' => $userId
        ])->save();
    }
}
