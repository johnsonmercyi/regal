<?php

namespace App\Http\Middleware;

use Closure;

class AuthControl
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

        // dd($request->user());
        if ($request->user() == null) {
            return redirect()->route('login');
        }
        return $next($request);
        // if (! $request->expectsJson()) {
        //     return route('login');
        // }
    }
}
