<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ForceHttps
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->secure() && (config('app.env') === 'production' || config('app.force_https'))) {
            return redirect()->secure($request->getRequestUri());
        }

        return $next($request);
    }
}
