<?php

namespace App\Http\Middleware;

use Closure;

class CheckAdmin
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
        $nameSplit = explode('/', $request->user()->name);

        if($nameSplit[0] !== 'admin'){
            // dd($request->route());
            return redirect()->route('login');
        }
        return $next($request);
    }
}
