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
        return $order->plans->reduce(function (Collection $carry, $plan) use ($subscriptions, $order) {

            $sub = $subscriptions->filter(function ($sub) use ($plan) { //Get current plan user already have
                return $sub->plan && $sub->plan->id === $plan->id;
            })->first();

            if (! $sub) {   //create plan user doesn't have
                $sub = Subscription::create()->forceFill(['plan_id' => $plan->id]);
            }

            if ($order->expires_at) {                                                // user has set expires time then set subscription to this time
                $sub->expires_at = $order->expires_at;
            } elseif ($sub->expires_at && now()->lessThan($sub->expires_at)) {       // subscription already have expires time then plus months
                $sub->expires_at = $sub->expires_at->addMonths($order->month);
            } else {                                                                 // otherwise
                $sub->expires_at = now()->addMonths($order->month);
            }

            $sub->save();

            return $carry->push($sub);

        }, collect());
    }
}
