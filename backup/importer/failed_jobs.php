<?php

return get('failed_jobs', new Mapper([
    'connection'    => 'string',
    'queue'       => 'string',
    'payload'    => 'string',
    'exception'    => 'string',
    'failed_at' => 'datetime',
]));
