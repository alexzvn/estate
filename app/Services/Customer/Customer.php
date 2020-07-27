<?php

namespace App\Services\Customer;

use App\Models\User;
use App\Services\Customer\Access\AccessManager;
use App\Services\Customer\Subscription\SubscriptionManager;

class Customer
{
    use SubscriptionManager;

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
}
