<?php

namespace App\Http\Middleware;

use Closure;

class authCheck
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
        
        session_start();
        
        $notLoggingIn = $request->server()["REQUEST_URI"] != "/login" &&
                    $request->server()["REQUEST_URI"] != "/login/user";

        if( $notLoggingIn && !isset($_SESSION['user']) ){
            return redirect('login');
        }

        if(isset($_SESSION['role']) && $_SESSION['role'] == "STD" && 
            $request->server()["REQUEST_URI"] != "/student/profile/".$_SESSION['user'] &&
            $request->server()["REQUEST_URI"] != "/student/result/".$_SESSION['user'] &&
            $notLoggingIn
        ){
            return redirect('login');
        }
        // dd(session('user'));$request->server()["REQUEST_URI"] != "/login/user" && 
        
        return $next($request);
        
    }
}
