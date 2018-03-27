<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
//use Illuminate\Foundation\Auth\User as Authenticatable;

class LoginController extends Controller
{
    /*
   |--------------------------------------------------------------------------
   | Login Controller
   |--------------------------------------------------------------------------
   |
   | This controller handles authenticating users for the application and
   | redirecting them to your home screen. The controller uses a trait
   | to conveniently provide its functionality to your applications.
   |
   */
    use AuthenticatesUsers;
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin/index';
    protected $username;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

        $this->middleware('guest:admin', ['except' => 'logout']);
    }
    /**
     * 重写登录视图页面
     * @author 晚黎
     * @date   2016-09-05T23:06:16+0800
     * @return [type]                   [description]
     */
    public function showLoginForm()
    {
        //dd('showLoginForm');
        return view('admin.auth.login');
    }
    /*
 * 具体登陆
 */
    public function login(Request $request)
    {
    //echo '11111';
    //dd(\Route::currentRouteAction(), \Route::current());
    //dd(\Route::currentRouteAction(), \Route::current());
    //\Route::current()  可以得到路由的一些信息
    /*+action: array:7 [▼
"middleware" => array:1 [▶]  中间间
"uses" => "App\Http\Controllers\Admin\AdminLoginController@login"
"controller" => "App\Http\Controllers\Admin\AdminLoginController@login"   控制器
"namespace" => "App\Http\Controllers\Admin" 命名空间
"prefix" => "/admin" 路由前辍
"where" => []
"as" => "login"   路由别名
]*/
    //\Route::current()->getActionMethod() 返回路由的方法 login
    //\Route::current()->getAction()['namespace']   返回路由的命名空间
    //\Route::current()->getActionName() 控制器名字 "App\Http\Controllers\Admin\AdminLoginController@login"
    //dd(\Route::currentRouteAction()); // 返回控制器及方法 "App\Http\Controllers\Admin\AdminLoginController@login"

    //dd(Auth::guard('admin')->check());
       // $this->validate($request, [
       //     'username' => 'required|min:2',
          //  'password' => 'required|min:5|max:30',
       // ]);

        $user = request(['email', 'password']);
        if (\Auth::guard('admin')->attempt($user)) {
    //dd(Auth::guard('admin')->check());
    //dd(Route::current_controller().'@'.current_action());
            $me = getUser('admin');
            setUserPermissions($me); //缓存 用户角色,用户权限，管理机构号pid
            //缓存所有权限
            // SetallPermissionCache();
            //缓存所有机构
           // SetInstitutionCache();
            //缓存所有菜单
           // SetMenuCache();

            
            return redirect('/admin/index'); //成功登陆
         //dd('登陆成功');
        }

        return \Redirect::back()->withErrors("用户名密码错误");
    }
    /**
     * 自定义认证驱动
     * @author 晚黎
     * @date   2016-09-05T23:53:07+0800
     * @return [type]                   [description]
     */
    protected function guard()
    {
        return auth()->guard('admin');
    }
    /**
     * Log the user out of the application.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        cache()->forget('user_'.getUserId('admin'));//清理当前用户缓存

        $this->guard('admin')->logout();
        request()->session()->flush();
        request()->session()->regenerate();
        //cacheClear();//清理所有缓存
        return redirect('/admin/login');
    }
    
    //protected function authenticated()
    //{
        //dd(\Auth::guard('admin')->user());
        // 缓存用户权限  及所在的机构号
       // setUserPermissions(\Auth::guard('admin')->user());
       // return redirect()->intended($this->redirectPath());
    //}

}
