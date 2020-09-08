<?php
namespace App\Contracts;

/**
 * Handler interface for pipeline
 */
interface Handler
{
    public function handle($value, \Closure $next);
}
