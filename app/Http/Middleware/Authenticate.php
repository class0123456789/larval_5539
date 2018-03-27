<?php

namespace App\Http\Middleware;

use Closure;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,$guard = null)
    {
       // dd(\Auth::guard($guard)->guest());
        if (\Auth::guard($guard)->guest()) {
            //dd($guard);
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else {
                $login_path = [
                    'admin' => '/admin/login',
                ];
                $url = empty($guard) ? '/login' : (isset($login_path[$guard]) ? $login_path[$guard] : '/login');
                return redirect()->guest($url);
            }
        }
//        if (\Auth::guard($guard)->check()) {
//            //dd($guard);
//            if ($request->ajax() || $request->wantsJson()) {
//                return response('Unauthorized.', 401);
//            } else {
//                $login_path = [
//                    'admin' => '/admin/index',
//                ];
//                $url = empty($guard) ? '/index' : (isset($login_path[$guard]) ? $login_path[$guard] : '/index');
//                return redirect($url);
//            }
//        }
        //dd($request);
        return $next($request);
    }
}
