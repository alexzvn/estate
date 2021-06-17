<?php

return get('subscriptions', new Mapper([
    'user_id'     => 'id.users',
    'plan_id'     => 'id.plans',
    'lock'        => 'boolean',
    'activate_at' => 'datetime',
    'expires_at'  => 'datetime',
    'updated_at'  => 'datetime',
    'created_at'  => 'datetime',
]));