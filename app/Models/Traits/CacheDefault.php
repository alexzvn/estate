<?php

namespace App\Models\Traits;

use Rennokki\QueryCache\Traits\QueryCacheable;

/**
 * 
 */
trait CacheDefault
{
    use QueryCacheable;

    public $cacheFor = 86400; // 1 day

    
    /**
     * Invalidate the cache automatically
     * upon update in the database.
     *
     * @var bool
     */
    protected static $flushCacheOnUpdate = true;
}
