<?php

namespace App\Repository;

use App\Models\Subscription as Model;

class Subscription extends BaseRepository
{
    public function __construct(Model $model) {
        $this->setModel($model);
    }

    /**
     * get model with default relation ship
     *
     * @param string|array $relation
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function withRelation($relation = null)
    {
        return self::with($relation ?? ['plan.categories', 'plan.provinces']);
    }
}
