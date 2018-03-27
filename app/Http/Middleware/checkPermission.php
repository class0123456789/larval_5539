<?php

namespace App\Http\Middleware;


use Closure;
use Route;

class checkPermission
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
        //return $next($request);
        //$this->checkPermission();
        if(isset(Route::current()->getAction()['controller'])) {//如果为重定向 直接返回真，不进行权限检查
          return $next($request) ;
        }
        return $this->checkPermission() ? $next($request) : abort(500, '没有权限访问');
    }
    /**
     * 验证用户权限
     * @author 晚黎
     * @date   2017-07-24T16:46:35+0800
     * @return [type]                   [description]
     */
    public function checkPermission()
    {
        //$routes = app('router')->getRoutes();
 //       $routes = Route::getRoutes()->getRoutesByMethod();
        
       //  $routes = Route::getRoutes()->getRoutesByName();
       // $att =[];
 //        dd($routes["HEAD"],$routes["GET"],Route::current(),Route::current()->getAction());
       // foreach ($routes as $route) {
       //     $att[] =$route;
        //$route->prepareForSerialization();
       // }
       // dd( $att);
            // dd(isset(Route::current()->getAction()['controller']));
        
        
        $method = $this->getCurrentControllerMethod();
        $actionName = $this->getCurrentControllerName();
        //dd(strtolower($actionName.'.'.$method));
        return true;
        //return haspermission(strtolower($actionName.'.'.$method));//返回true,false
    }
    /**
     * 获取当前控制器方法
     * @author 晚黎
     * @date   2017-07-24T14:23:52+0800
     * @return [type]                   [description]
     */
    private function getCurrentControllerMethod()
    {
        return $this->getCurrentActionAttribute()['method'];
    }
    /**
     * 获取当前控制器名称
     * @author 晚黎
     * @date   2017-07-24T14:24:04+0800
     * @return [type]                   [description]
     */
    private function getCurrentControllerName()
    {
        return $this->getCurrentActionAttribute()['controller'];
    }
    /**
     * 获取当前控制器相关属性
     * @author 晚黎
     * @date   2017-07-24T14:24:14+0800
     * @return [type]                   [description]
     */
    private function getCurrentActionAttribute()
    {
        
        $action = Route::currentRouteAction();
        if(!$action){//这里如果是重定向 那么action 将不会存在所以都反回空
            return ['controller' => '', 'method' => ''];
        }
        //dd($action);
        list($class, $method) = explode('@', $action);
        return ['controller' => class_basename($class), 'method' => $method];
    }
}
