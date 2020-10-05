<?php

namespace App\Http\Middleware;

use Closure;

class ExtensionImport
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (config('app.extension.protect') === false) {
            return $next($request);
        }

        $key = $request->key ?? $request->token ?? false;

        if ($key && hash_equals(config('app.extension.key'), $key)) {
            return $next($request);
        }

        abort(401);
    }
}
