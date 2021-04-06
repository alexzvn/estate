<?php

return get('sms_template', new Mapper([
    'name'        => 'string',
    'content'     => 'string',
    'user_id'     => 'id.users',
    'updated_at'  => 'datetime',
    'created_at'  => 'datetime',
]));
