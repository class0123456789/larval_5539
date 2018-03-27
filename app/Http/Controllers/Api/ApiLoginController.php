<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Client;
use App\Models\User;
//use Socialite;//微信登陆 要安装

use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Validator;


class ApiLoginController extends ApiController
{
    use AuthenticatesUsers;

    public function __construct()
    {
        //$this->middleware('auth:api')->only([
        //    'logout'
        //]);
        //$this->middleware('auth:api');
        //$this->middleware('guest')->except(['logout',]);
    }

    public function username()
    {
        return 'email';
    }

    // 登录 生成令牌
    public function login(Request $request)
    {
        //dd($request);

        $validator = Validator::make($request->all(), [
            'email'    => 'required|exists:users',
            'password' => 'required|between:5,32',
        ]);

        if ($validator->fails()) {
            $request->request->add([
                'errors' => $validator->errors()->toArray(),
                'code' => 401,
            ]);
            return $this->sendFailedLoginResponse($request);
        }
        //dd($request);
        //only 方法会返回所有你指定的键值对  返回用户名与密码
        $credentials = $this->credentials($request);//用户名与密码
        //$user = request(['email', 'password']);
        //$ApiUser = ApiUser::where('')
        //dd( $request->all()['iphone','password']);
        //if(\Auth::guard('api')->attempt(['email'=>request('email'),'password'=>request('password')])){
        //    echo '1111111';die();
        //}else{
        //    echo '0000000';die();
        //};
        //dd(\Auth::guard('auth:api')->attempt(['email'=>request('email'),'password'=>request('password')]));
        //dd($this->guard('api')->attempt($credentials,false));
        if ($this->guard('api')->attempt($credentials, $request->has('remember'))) {
            //dd(\Auth::guard('api')->user());
            return $this->sendLoginResponse($request);
        }
        //dd($this->guard('api'));
        //if (\Auth::guard('api')->attempt($credentials,$request->has('remember'))) {
        //    return $this->sendLoginResponse($request);
        //}
        //$user = request(['iphone', 'password'])
        //if (\Auth::guard('api')->attempt($credentials)) {
        //    $user = Auth::user();
        //    dd($user);
        //    return $this->sendLoginResponse($request); //成功登陆
        //}
        //dd( $this->guard('api'));

        return $this->setStatusCode(401)->failed('登录失败');
    }

    // 退出登录
    public function logout()
    {
        //dd(\Auth::guard(api)->user());

        if (Auth::guard('api')->check()){

            Auth::guard('api')->user()->token()->revoke();
            //Auth::guard('api')->user()->token()->delete();

        }

        return $this->message('退出登录成功');

    }

    // 第三方登录
    public function redirectToProvider($driver) {

        if (!in_array($driver,['qq','wechat'])){

            throw new NotFoundHttpException;
        }

        return Socialite::driver($driver)->redirect();
    }

    // 第三方登录回调
    public function handleProviderCallback($driver) {

        $user = Socialite::driver($driver)->user();

        $openId = $user->id;

        // 第三方认证
        $db_user = User::where('xxx',$openId)->first();

        if (empty($db_user)){

            $db_user = User::forceCreate([
                'phone' => '',
                'xxUnionId' => $openId,
                'nickname' => $user->nickname,
                'head' => $user->avatar,
            ]);

        }

        // 直接创建token

        $token = $db_user->createToken($openId)->accessToken;

        return $this->success(compact('token'));

    }

    //调用认证接口获取授权码
    protected function authenticateClient(Request $request)
    {
        $credentials = $this->credentials($request);

        // 个人感觉通过.env配置太复杂，直接从数据库查更方便
        $password_client = Client::query()->where('password_client',1)->latest()->first();


        $request->request->add([
            'grant_type' => 'password',
            'client_id' => $password_client->id,
            'client_secret' => $password_client->secret,
            'username' => $credentials['email'],
            'password' => $credentials['password'],
            'scope' => ''
        ]);

        //路由 /oauth/token 返回的 JSON 响应中会包含 access_token 、refresh_token 和 expires_in 属性。
        //expires_in 属性包含访问令牌的有效期（单位：秒）。
        //像 /oauth/authorize 路由一样，/oauth/token 路由在 Passport::routes 方法中定义了。
        $proxy = Request::create(
            'oauth/token',
            'POST'
        );
        //dispatch在配置了队列并且Job需要在队列里执行的情况下会被放到队列里,否则自动调用dispatchNow
        //dispatchNow 直接执行(同步执行)Job
        $response = \Route::dispatch($proxy);
        //dd(Auth::user()->toArray());
        return $response;
    }

    protected function authenticated(Request $request)
    {
        return $this->authenticateClient($request);
    }

    protected function sendLoginResponse(Request $request)
    {
        //dd($request);
       // $this->clearLoginAttempts($request);

        return $this->authenticated($request);
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        $msg = $request['errors'];
        $code = $request['code'];
        return $this->setStatusCode($code)->failed($msg);
    }
    //测试令牌
    public function details(Request $request){

        //return $this->message('退出登录成功');
        //dd(Auth::guard('api')->user()->employee());
        //$user=Auth::guard('api')->user()->toArray();
        //dd($user);
       //return response()->json(['user'=>Auth::guard('api')->user()]);
            $user = User::with('employee')->find(getUserId('api'));
            //dump($user->employee);//返回employee对象
            $u= $user->toArray();//返回employee对象
            //dd($u);
            //dump($user->employee->institutions()->first()->toarray());
            $u['institution'] = $user->employee->institution->toarray();
            //dd( $u);//返回institution对象的集合
        
        return $this->message($u);
       // $proxy = Request::create(
       //     'oauth/clients',
       //     'POST'
       // );
        //return $response = dispatch($proxy);
    }
}
