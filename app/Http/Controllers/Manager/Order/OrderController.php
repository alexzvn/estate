<?php

namespace App\Http\Controllers\Manager\Order;

use App\Repository\Order;
use App\Http\Controllers\Manager\Controller;
use App\Http\Requests\Manager\Order\UpdateOrder;
use App\Models\Order as ModelsOrder;
use App\Repository\Permission;
use App\Repository\Plan;
use App\Services\Customer\Customer;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('manager.order.view');

        $order = Order::with(['plans', 'customer.note', 'creator'])
        ->whereHas('customer')
        ->filter($request)
        ->latest();

        return view('dashboard.order.index', [
            'orders' => $order->paginate(40),
            'plans' => Plan::get(),
            'staff' => Permission::findUsersHasPermission('manager.dashboard.access')
        ]);
    }

    public function view(string $id, Order $order)
    {
        $this->authorize('manager.order.view');

        $order = $order->findOrFail($id);

        return view('dashboard.order.view', [
            'customer' => $order->customer,
            'plans' => $order->plans,
            'order' => $order,
        ]);
    }

    public function delete(string $id, Order $order)
    {
        $this->authorize('manager.order.delete');

        $order->findOrFail($id)->delete();

        return redirect(route('manager.order'))->with('success', 'Xóa thành công');
    }

    public function update(string $id, UpdateOrder $request)
    {
        $order = Order::findOrFail($id)->forceFill(['manual' => (bool) $request->manual]);

        if (! $order->isActivated() || user()->can('manager.order.modify.force')) {
            $request->manual ?
                $this->updateManual($order, $request)->save():
                $this->updateAuto($order, $request)->save();
        }

        if (! empty($request->note)) {
            $order->writeNote($request->note);
        }

        if ($request->active) {
            return $this->activate($id, $request);
        }

        return redirect(route('manager.order.view', ['id' => $order->id]));
    }

    public function activate(string $id, Request $request)
    {
        $this->authorize('manager.order.modify');

        $order = Order::findOrFail($id);

        if ($order->isActivated()) {
            return back()->with('danger', 'Bạn không đủ thẩm quyền để kích hoạt gói này');
        }

        $activated_at = $request->activated_at ?
            Carbon::createFromFormat('d/m/Y', $request->activated_at) : now();

        (new Customer($order->customer))->renewSubscription($order);

        $order->forceFill([
            'activate_at' => $activated_at,
            'verifier_id' => user()->id,
        ])->save();

        return back()->with('success', 'Đã kích hoạt gói này');
    }

    public function verify(string $id, Request $request)
    {
        $this->authorize('*');

        $order = Order::findOrFail($id)->verify();

        return back()->with('success', 'Đã xác thực đơn hàng');
    }

    protected function updateAuto(ModelsOrder $order, UpdateOrder $request)
    {
        $order->fill($request->all())->fill([
            'month' => (int) $request->expires_month,
            'price' => $order->plans->sum('price') ?? 0,
            'status'=> $request->verified ? ModelsOrder::PAID : ModelsOrder::PENDING,
            'verified' => (bool) $request->verified,
            'expires_at' => $request->expiresAt(),
        ]);

        return $this->orderCalcDiscount($order, $order->price * $order->month);
    }

    protected function updateManual(ModelsOrder $order, UpdateOrder $request)
    {
        $price = (int) str_replace(',', '', $request->price ?? 0);

        $order->fill($request->all())->fill([
            'month' => null,
            'price' => $price,
            'status'=> $request->verified ? ModelsOrder::PAID : ModelsOrder::PENDING,
            'verified' => (bool) $request->verified,
            'expires_at' => $request->expiresAt(),
        ]);

        return $this->orderCalcDiscount($order, $order->price);
    }

    private function orderCalcDiscount(ModelsOrder $order, int $price)
    {
        if ($order->discount_type == ModelsOrder::DISCOUNT_NORMAL) {
            $price -= $order->discount;
        } else {
            $price -= $price * ($order->discount/100);
        }

        $order->after_discount_price = $price;

        return $order;
    }
}
