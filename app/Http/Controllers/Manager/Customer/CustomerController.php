<?php

namespace App\Http\Controllers\Manager\Customer;

use App\Models\Order;
use App\Repository\Plan;
use App\Repository\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Repository\Permission;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Manager\Controller;
use App\Http\Requests\Manager\Customer\StoreCustomer;
use App\Http\Requests\Manager\Customer\AssignCustomer;
use App\Http\Requests\Manager\Customer\UpdateCustomer;
use App\Http\Requests\Manager\Customer\Order\StoreOrder;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('manager.customer.view');

        $users = User::with(['subscriptions', 'supporter', 'orders', 'logs', 'note'])
            ->filter($request)
            ->onlyCustomer();

        if (! empty($request->expires_last) || ! empty($request->expires)) {
            $users = $users->whereHas('subscriptions', function ($q) use ($request) {
                $q->filter($request);
            });
        }

        $users = $users->latest()->paginate(40);

        return view('dashboard.customer.index', [
            'users' => $users,
            'staff' => Permission::findUsersHasPermission('manager.customer.view')
        ]);
    }

    public function view(string $id, User $user)
    {
        $this->authorize('manager.customer.view');

        $this->rememberLastUrl();

        $user = $user->with(['permissions', 'subscriptions.plan'])
            ->onlyCustomer()
            ->findOrFail($id);

        if ($user->supporter_id !== auth()->id() && user()->cannot('manager.user.assign.customer')) {
            abort(403);
        }

        $staffs = Permission::findUsersHasPermission('manager.dashboard.access');

        return view('dashboard.customer.view', [
            'plans' => Plan::all(),
            'staffs' => $staffs,
            'user' => $user,
        ]);
    }

    public function create()
    {
        $this->authorize('manager.customer.create');

        $this->rememberLastUrl();

        return view('dashboard.customer.create');
    }

    public function storeAndExit(StoreCustomer $request, User $user)
    {
        $this->store($request, $user);

        return redirect($this->pullLastUrl());
    }

    public function updateAndExit(UpdateCustomer $request)
    {
        $this->update($request);

        return redirect($this->pullLastUrl());
    }

    public function store(StoreCustomer $request, User $user)
    {
        $user->fill($request->all());

        if (empty($user->email)) {
            $user->email = Str::random(10) . '@' . $request->getHttpHost();
        }

        $user->save();

        if ($request->user()->cannot('*')) {
            $this->assignCustomerToUser($user, Auth::id());
        }

        return redirect(route('manager.customer.view', ['id' => $user->id]));
    }

    public function update(UpdateCustomer $request)
    {
        $user = $request->updateUser;

        $user->fill($request->all())->save();

        if (! empty($request->note)) {
            $user->writeNote($request->note);
        }

        if ( ! empty($request->password)) {
            $user->emptySession();
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

    public function delete(string $id)
    {
        $this->authorize('manager.customer.delete');

        User::findOrFail($id)->forceDelete();

        return redirect(route('manager.customer'))->with('success', 'Đã xóa tài khoản này');
    }

    public function logout(string $id)
    {
        $this->authorize('manager.customer.logout');

        User::findOrFail($id)->emptySession();

        return back()->with('success', 'Đã đăng xuất người dùng này');
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

    public function take(string $id, User $user)
    {
        $this->authorize('manager.customer.take');

        $user = $user->findOrFail($id);

        if ( ! (empty($user->supporter) || Auth::user()->can('*'))) {
            return back()->withErrors(['error' => 'Đã có người nhận quản lý khách hàng này rồi']);
        }

        $user->supporter_id = Auth::id();

        $user->save();

        return back()->with('success', 'Đã nhận quản lý khách hàng này');
    }

    public function untake(string $id, User $user)
    {
        $this->authorize('manager.customer.take');

        $user = $user->findOrFail($id);

        if ($user->supporter_id === Auth::id() || Auth::user()->can('*')) {
            $user->forceFill(['supporter_id' => null])->save();
            return back()->with('success', 'Đã bỏ quản lý khách hàng này');
        }

        return back();
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
        $order->forceFill(['creator_id' => $request->user()->id])->save();

        return redirect(route('manager.order.view', ['id' => $order->id]));
    }

    private function assignCustomerToUser($customer, $userId = null)
    {
        $customer->forceFill([
            'supporter_id' => $userId
        ])->save();
    }

    protected function rememberLastUrl()
    {
        if (
            preg_match('/manager\/customer\/create/', url()->previous()) ||
            preg_match('/manager\/customer\/(.*?)\/view/', url()->previous())
        ) {
            return;
        }

        return request()->session()->put(
            'manager.customer.last.link',
            url()->previous(route('manager.customer', [], false))
        );
    }

    protected function pullLastUrl()
    {
        return request()->session()->pull('manager.customer.last.link') ??
            route('manager.customer');
    }
}
