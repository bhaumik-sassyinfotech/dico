<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Admin
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
        
        if (! Auth::check())
        {
            return redirect('/');
        }
        if(Auth::user()->first_login == 0)
        {
            return redirect()->route('security.firstLogin');
        }
        return $next($request);
    }
}
