<?php

namespace App\Http\Middleware;

use Closure;
use Route, URL;

class AuthenticateAdmin
{
    protected $except = [
        'admin/index',
    ];
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //dd($routes = Route::getRoutes()->getRoutesByMethod(),Route::current());
        if(!\App\Models\Permission::all()->count()){//为空的时侯白言女禾
                $routes = Route::getRoutes()->getRoutesByMethod();

               //  $routes = Route::getRoutes()->getRoutesByName();
                 $attr =[];
                 //$pattern='/\//';  /*因为/为特殊字符，需要转移*/
                 //$surl='';
                 //dd($routes,Route::current(),Route::current()->getAction());
                 //取出所有的admin开头的地址
                foreach ($routes as $k0=>$v0) {            
                    foreach ($v0 as $k1=>$v1) {
                       // dd($routes,$k1,$v1,$v1->action['as']);
                      
                        if(starts_with($k1, 'admin/') ){
                            if(isset($v1->action['as'])){
                                if(!isset($attr[$v1->action['as']])){ 
                                   $attr[$v1->action['as']]=$v1->uri; 
                                   //dd($attr[$v1->action['as']]);
                               } 
                            } else {
                                 if(!isset($attr[$k1])){
                                   $attr[$k1]=$v1->uri; 
                                    //dd($attr[$k1]);
                               } 
                            }
                            //$str=preg_split ($pattern, $k1);
                            //$surl='';
                            //foreach($str as $strk=>$strkv)
                            //{
                            //    dd($routes,$strk,$strkv);
                            //    if(!starts_with($strkv, '{')){ //取消当中的参数
                            //        $surl.="/".$strkv;                            
                            //    }
                            //}
                            //$surl = substr($surl,1);//把最前面的'/'去掉
                            //dd($surl);
                          //if(!isset($attr[$k0][$k1])){ $attr[$k0][$k1]=$v1->uri; } 
                           
                           //if(!isset($attr[$surl])){ $attr[$surl]=$v1->uri; } 
                           //unset($str);
                        }                                      
                    }
                    // $att[] =$route;
                    //$route->prepareForSerialization();
                }
                //unset($surl);
                //unset($pattern);
                //dd($attr);
                foreach($attr as $k=>$v){
                    \App\Models\Permission::Create(
                            [
                                'slug' => $k,
                                'name' => $k,
                                'description' =>$v,
                        ]);
                }
                unset($attr);
        
        }
        
        //dump(haspermission('admin/logout'));
            //$route->prepareForSerialization();

//        if (\Auth::guard('admin')->user()->id === 1) {
//            return $next($request);
//        }
        //dd(\Auth::guard('admin')->user());
        //$user = \Auth::guard('admin')->user();
        //$roles= $user->roles;//得到用户角色
        //dd($user_roles);
        
        //$user_roles_permissions=$user->userRolesPermissions($roles);//得到所有角色权限
        //$user_permissions = $user->permissions;//取得所有用户的权限
        //$allPermissions = \App\Models\Permission::all()->pluck('slug');
        //dump($user_roles_permissions,$user_permissions);
        // $permissions = $user_roles_permissions->merge($user_permissions);//用户及用户角色所拥有的所有权限
        //dd($permissions->pluck('slug'), $allPermissions);
        
        //$perm = \App\Models\Permission::find(28);
        //dd($user->hasPermission($perm)); //用户是否有权限
        //dd($user_roles_permissions,$user_permissions);
        //$user = \App\Models\AdminUser::find(1);
        //$user_roles=$user->roles()->get()->pluck('slug');//得到所有角色权限
        //$user_permissions = $user->permissions()->get()->pluck('slug');
        //dd($user_roles,$user_permissions);
        $previousUrl = \URL::previous();
        //dump(Route::current(),Route::current()->uri);
        // 对路由分三种情况来解析
        $r_name = \Route::currentRouteName();// 1 取路由别名 Name
        //dd($r_name);
        
        //dd($r_name);
        if(!$r_name){//没有名称， 为空的时候,取当前路由的uri
             //return $next($request);
            $r_name = Route::current()->uri;//2 当前访问的uri 取路由uri
          //    dump($r_name);
        }
        if ($request->ajax() ){//3 对自已定义的ajax的后台路由 把前辍去掉 uri路由中以admin/开头,而admin路由表中写法是不带这个前辍的
                    if(starts_with($r_name, 'admin/') ){
                        $r_name =str_replace("admin/","",$r_name);
                        
                    }
       }
       //测试ajax路由的返回情况写法
//         return response()->json([
//                    'status' => -1,
//                    'code'   => 403,
//                    'data'    => $r_name,
//                ]);
        if($r_name){//名不为空
          
            if(haspermission($r_name)){
                 return $next($request);
            } 
        }
          
        //dd(Route::current());
        //if($r_name ==='admin/'){
        //    $r_name= 'admin/index';
        //}
        //if(starts_with($r_name, 'admin/') ){
        //    $r_name =str_replace("admin/","",$r_name);
        //}
        //dd(Route::current(),$r_name);
        //if(starts_with($r_name, '/admin/') ){
        //    $r_name =str_replace("/admin/","",$r_name);
        //}
       
        //if(haspermission($routeName)){
       // if(haspermission($r_name)){
       //     return $next($request);
       // }
        //dd(\Route::current());
        //if (!\Gate::forUser($user)->check($routeName)) {
            //if ($request->ajax() && ($request->getMethod() != 'GET')) {
      //      if ($request->ajax() ) {
               // return $request;
                //return $next($request);
               // return response()->json([
               //     'status' => -1,
               //     'code'   => 403,
               //     'msg'    => '您没有权限',
               // ]);
                
         //                       return response()->json([
         //           'status' => 1,
         //           'code'   => 200,
         //           'msg'    =>Route::current()->uri,
         //       ]);
         //   } 
            //else {
                return response()->view('admin.errors.403', compact('previousUrl'));
            //}
        //}
        //return $next($request);
    }
}
