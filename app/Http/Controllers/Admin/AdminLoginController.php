<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    use AuthenticatesUsers;
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin';
    protected $username;
    //
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
        return view('admin.auth.login');
    }

    //public function index()
    //{
    //    return view('/admin/login/index');
    //}

    /*
     * 具体登陆
     */
    //public function login(Request $request)
    //{
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
//        $this->validate($request, [
//            'username' => 'required|min:2',
//            'password' => 'required|min:5|max:30',
//        ]);

//        $user = request(['username', 'password']);
//        if (true == \Auth::guard('admin')->attempt($user)) {
            //dd(Auth::guard('admin')->check());
            //dd(Route::current_controller().'@'.current_action());
//            return redirect('/home'); //成功登陆
            //
//        }

//        return \Redirect::back()->withErrors("用户名密码错误");
//    }

    /*
     * 登出操作
     */
    public function logout()
    {
        \Auth::guard('admin')->logout();
        request()->session()->flush();
        request()->session()->regenerate();
        cacheClear();//清理缓存
        return redirect('/admin/login');
    }
}
