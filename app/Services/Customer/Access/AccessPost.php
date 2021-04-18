<?php

namespace App\Services\Customer\Access;

use App\Models\Plan;
use Illuminate\Support\Collection;

class AccessPost
{
    /**
     * All Subscriptions
     * 
     * @var \App\Models\Subscription[]|\Illuminate\Support\Collection
     */
    protected $subs;

    /**
     * Current customer plans
     *
     * @var array
     */
    protected array $plan = [];

    public function __construct(Collection $subs) {
        $this->subs = $subs;
        $this->mapPlans();
    }

    /**
     * Check if user can access type of post
     *
     * @param integer $type
     * @return boolean
     */
    public function canAccess(int $type)
    {
        return isset($this->plan[$type]);
    }

    /**
     * Get available categories ids
     *
     * @param integer $type
     * @return integer[]
     */
    public function categories(int $type)
    {
        return $this->canAccess($type) ? $this->plan[$type]['categories'] : [];
    }

    /**
     * Get available provinces ids
     *
     * @param integer $type
     * @return integer[]
     */
    public function provinces(int $type)
    {
        return $this->canAccess($type) ? $this->plan[$type]['provinces'] : [];
    }

    /**
     * Get accessible type of post
     *
     * @return int[]
     */
    public function types()
    {
        return array_keys($this->plan);
    }

    protected function mapPlans()
    {
        $this->plans()->each(function (Plan $plan) {
            foreach ($plan->types as $type) {
                $this->plan[$type]['categories'] ??= [];
                $this->plan[$type]['provinces']  ??= [];

                array_push($this->plan[$type]['categories'], ...$plan->categories);
                array_push($this->plan[$type]['provinces'], ...$plan->provinces);
            }
        });

        foreach ($this->plan as $type => $item) {
            $this->plan[$type]['categories'] = array_unique($this->plan[$type]['categories']);
            $this->plan[$type]['provinces'] = array_unique($this->plan[$type]['provinces']);
        }
    }

    /**
     * Get all plans available from all subscriptions 
     *
     * @return Illuminate\Support\Collection
     */
    private function plans()
    {
        return $this->subs->map(fn($item) => $item->plan);
    }
}
