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
        if (Auth::guard($guard)->check()) {
            $url = $guard ? $guard.'/index':'/index';
            return redirect($url );
        }
        //dd($guard) ;
//        if (\Auth::guard('admin')->check()) {
//            //dd($guard) ;
//            //$url = $guard ? '/admin/index':'/home';
//            //return redirect('/admin/index');
//            //echo url()->current();
//            return redirect('/admin/index');
//            //return Redirect::to('/admin');
//        }
//
//        if (\Auth::guard()->check()) {
//            //dd($guard) ;
//          //  $url = $guard ? '/admin/index':'/home';
//            return redirect('/');
//        }

        return $next($request);
    }
}
