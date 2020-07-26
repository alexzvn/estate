<?php

namespace App\Http\Controllers\Manager\Order;

use App\Repository\Plan;
use App\Repository\Order;
use App\Http\Controllers\Manager\Controller;
use App\Http\Requests\Manager\Order\UpdateOrder;
use App\Models\Order as ModelsOrder;
use App\Models\User;
use App\Repository\Subscription;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class OrderController extends Controller
{
    public function store(string $id)
    {

    }

    public function view(string $id, Order $order)
    {
        $order = $order->findOrFail($id);

        return view('dashboard.order.view', [
            'customer' => $order->customer,
            'plans' => $order->plans,
            'order' => $order,
        ]);
    }

    public function update(string $id, UpdateOrder $request)
    {
        $order = Order::findOrFail($id);

        if ($request->expires_at) {
            $expires = Carbon::createFromTimestamp(strtotime($request->expires_at));
        } elseif($request->expires_month ) {
            $expires = now()->addMonths($request->expires_month);
        } else {
            $expires = null;
        }

        $startAt = $request->activated_at ? Carbon::createFromTimestamp(strtotime($request->activated_at)) : now();

        if (! $order->verified || $order->status === ModelsOrder::PENDING) {
            $this->activateSubscription($order->customer, $order->plans, $expires);
        }

        $order->fill($request->all())->fill([
            'month' => (int) $request->expires_month,
            'price' => $order->plans->sum('price') ?? 0,
            'status'=> ModelsOrder::PAID,
            'verified' => true,
            'activate_at' => $startAt,
            'expires_at' => $expires,
        ]);

        $price = $order->price * $order->month;

        if ($order->discount_type == ModelsOrder::DISCOUNT_NORMAL) {
            $price -= $order->discount;
        } else {
            $price -= $price * ($order->discount/100);
        }

        $order->after_discount_price = $price;

        $order->save();

        return redirect(route('manager.order.view', ['id' => $order->id]));
    }

    protected function activateSubscription(User $customer, Collection $plans, Carbon $expires = null)
    {
        $subscriptions = $customer->subscriptions()->with('plan')->get();

        $subscriptions = $plans->reduce(function (Collection $carry, $item) use ($subscriptions, $expires) {

            $sub = $subscriptions->filter(function ($sub) use ($item) { return $sub->plan->id === $item->id; })->first();

            if (! $sub) {
                $sub = Subscription::create();
                $sub->plan_id = $item->id;
                $sub->save();
            }

            $sub->expires_at = $expires;

            return $carry->push($sub);

        }, collect());

        $customer->subscriptions()->saveMany($subscriptions);
    }
}
