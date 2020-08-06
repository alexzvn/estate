<?php

namespace App\Services\Customer\Access;

use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Support\Collection;
use stdClass;

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
    protected $plan = [];

    /**
     * Default access
     *
     * @var string|null
     */
    protected $type = null;

    public function __construct(Collection $subs, string $defaultType = null) {
        $this->subs = $subs;
        $this->type = $defaultType;
        $this->mapPlans();
    }

    public function canAccess(string $type = null)
    {
        return isset($this->plan['type'][$type ?? $this->type]);
    }

    public function categories(string $type = null)
    {
        return $this->plan['type'][$type ?? $this->type]['categories'] ?? [];
    }

    public function provinces(string $type = null)
    {
        return $this->plan['type'][$type ?? $this->type]['provinces'] ?? [];
    }

    /**
     * Get accessible type of post
     *
     * @return string[]
     */
    public function types()
    {
        return $this->plan['accessible'] ?? [];
    }

    protected function mapPlans()
    {
       $this->plans()->each(function (Plan $plan)
       {
            foreach ($plan->types ?? [] as $type) {
                $this->plan['type'][$type] = ['categories' => [], 'provinces' => []];
                $this->plan['accessible'][] = $type;
            }

            $this->plan['accessible'] = array_unique($this->plan['accessible']);

       })->each(function (Plan $plan)
       {
           foreach ($plan->types ?? [] as $type) {
               array_push($this->plan['type'][$type]['categories'], ...$this->getIds($plan->categories));
               array_push($this->plan['type'][$type]['provinces'], ...$this->getIds($plan->provinces));
           }
       });
    }

    protected function getIds(Collection $models)
    {
        return $models->reduce(function ($carry, $item)
        {
            $carry[] = $item->id;

            return $carry;
        }, []);
    }

    private function plans()
    {
        return $this->subs->map(function ($item)
        {
            return $item->plan;
        })->filter(function ($item)
        {
            return $item !== null;
        });
    }
}