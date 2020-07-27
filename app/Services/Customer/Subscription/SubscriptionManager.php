<?php

namespace App\Services\Customer\Subscription;

use App\Models\Order;
use App\Repository\Subscription;
use Illuminate\Support\Collection;

trait SubscriptionManager
{
    public function renewSubscription(Order $order)
    {
        $currentSubs = $this->customer->subscriptions()->with('plan')->get();

        $newSubs = $this->makeSubscriptions($order, $currentSubs, $order->expires);

        $this->customer->subscriptions()->saveMany($newSubs);
    }

    private function makeSubscriptions(Order $order, $subscriptions)
    {
        return $order->plans->reduce(function (Collection $carry, $item) use ($subscriptions, $order) {

            $sub = $subscriptions->filter(function ($sub) use ($item) {
                return $sub->plan->id === $item->id;
            })->first();

            if (! $sub) {
                $sub = Subscription::create()->forceFill(['plan_id' => $item->id]);
            }

            $sub->expires_at = $order->expires_at;

            return $carry->push($sub);

        }, collect());
    }
}
