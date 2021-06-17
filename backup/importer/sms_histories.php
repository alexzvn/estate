<?php

return get('sms_histories', new Mapper([
    'recipient'       => 'string',
    'content'         => 'string',
    'sms_template_id' => 'id.sms_templates',
    'user_id'         => 'id.users',
    'updated_at'      => 'datetime',
    'created_at'      => 'datetime',
]));
