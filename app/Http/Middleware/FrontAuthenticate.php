<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class FrontAuthenticate
{
    public function handle($request, Closure $next)
   {
       //If request does not comes from logged in admin
       //then he shall be redirected to admin Login page
       // dd(Auth::guard('admin')->check());
       if (! Auth::guard('front')->check()) {
           return redirect('/users-login');
       }

       return $next($request);
   }
}
