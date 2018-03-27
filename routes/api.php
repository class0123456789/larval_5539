<?php
header('Access-Control-Allow-Origin:*'); // 指定允许其他域名访问
header('Access-Control-Allow-Headers:Authorization');// 响应头设置，允许设置Authorization这个http头
header('Access-Control-Allow-Methods:POST,GET');// 响应类型
use Illuminate\Http\Request;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->post('/user', function (Request $request) {
    //返回当前用户信息 携带令牌
    //return $request->user();
    return Auth::guard('api')->user();
    //return '11111';
});

Route::group(['namespace' => 'api'], function () {
    //登陆并获得令牌
    Route::post('/login', 'ApiLoginController@login');

    //Route::post('details', 'ApiLoginController@details');
});

Route::group(['middleware' => 'auth:api', 'namespace' => 'api'], function() {
    //dd('details');
    //dd(\Auth::user());
    //测试  携带令牌
    Route::post('/details', 'ApiLoginController@details');
    //退出 携带令牌
    Route::post('/logout', 'ApiLoginController@logout');
});
