<?php

namespace App\Services\Customer\Access;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Support\Collection;

class AccessManager
{
    protected $customer;

    private $subscriptions;

    public function __construct(User $customer) {
        $this->customer = $customer;

        $this->subscriptions = $this->customer->subscriptions()->active()
            ->with(['plan', 'plan.categories', 'plan.provinces'])->get();
    }

    public function getPostTypes()
    {
        return $this->plans()->reduce(function ($carry, Plan $item)
        {
            if (! empty($item->types)) {
                return $carry->concat($item->types);
            }

            return $carry;
        }, collect())->unique();
    }

    public function getCategories()
    {
        return $this->plans()->reduce(function (Collection $carry, $item)
        {
            if ($item && $item->categories) {
                return $carry->concat($item->categories->map(function ($cat)
                {
                    return $cat->id;
                }));
            }

            return $carry;
        }, collect())->unique();
    }

    public function getProvinces()
    {
        return $this->plans()->reduce(function (Collection $carry, $item)
        {
            if ($item && $item->provinces) {
                return $carry->concat($item->provinces->map(function ($province)
                {
                    return $province->id;
                }));
            }
            return $carry;
        }, collect())->unique();
    }

    /**
     * Get all plan customer have
     *
     * @return App\Models\Plan[]|\Illuminate\Support\Collection
     */
    public function plans()
    {
        return $this->subscriptions
            ->filter(function ($sub)
            {
                if (empty($sub->plan)) {
                    $sub->delete();
                }

                return $sub->plan;
            })->map(function ($sub)
            {
                return $sub->plan;
            });
    }
}
