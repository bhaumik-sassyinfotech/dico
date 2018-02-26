<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
<<<<<<< HEAD
        /*if (Auth::guard($guard)->check()) {
            return redirect('/home');
        }*/
=======
>>>>>>> 97831573c32ebc66af0c2a1dcbba864a0c0c8464
        if (Auth::check()) {
            return redirect()->route('index');
        }

        return $next($request);
    }
}
