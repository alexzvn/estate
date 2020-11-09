<?php

return array_reduce(get('plans'), function ($carry, $plan) {
    $plan = (object) $plan;

    foreach ($plan->types as $type) {
        array_push($carry, [
            'type'    => $type,
            'plan_id' => id('plans', $plan->_id['$oid'])
        ]);
    }

    return $carry;
}, []);