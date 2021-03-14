<?php

namespace App\Services\Customer\Access;

use App\Models\User;

class AccessManager
{
    protected $customer;

    private $subscriptions;

    public function __construct(User $customer) {
        $this->customer = $customer;

        $this->subscriptions = $this->customer->subscriptions()->active()
            ->with(['plan', 'plan.categories', 'plan.provinces'])->get();
    }

    public function post(string $type = null)
    {
        return new AccessPost($this->subscriptions, $type);
    }
}
