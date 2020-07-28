<?php

namespace App\Services\Customer;

use App\Models\User;
use App\Services\Customer\Access\AccessManager;
use App\Services\Customer\Subscription\SubscriptionManager;
use Illuminate\Support\Traits\ForwardsCalls;

class Customer
{
    use SubscriptionManager, ForwardsCalls;

    protected $customer;

    public function __construct(User $customer) {
        $this->customer = $customer;
    }

    /**
     * Get access Feature
     *
     * @return \App\Services\Customer\Access\AccessManager
     */
    public function access()
    {
        return new AccessManager($this->customer);
    }

    public function __call(string $method, array $args = [])
    {
        return $this->forwardCallTo($this->customer, $method, $args);
    }

    public function __get(string $key)
    {
        return $this->customer->$key;
    }
}
