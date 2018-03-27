<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

    Route::get('/login', 'LoginController@showLoginForm')->name('admin.login');
    Route::post('/login', 'LoginController@login');
    Route::get('/logout', 'LoginController@logout');
    Route::post('/logout', 'LoginController@logout');

    Route::get('/', 'IndexController@Index');







Route::group(['middleware' => ['auth:admin', 'check.permission']], function () {
    //Route::group(['middleware' => ['auth:admin', 'menu', 'authAdmin']], function () {
       
 Route::get('/dash','DashboardController@index')->name('admin.dash');
 Route::get('/i18n', 'DashboardController@dataTableI18n')->name('admin.i18n');
 
Route::get('index', ['as' => 'admin.index', 'uses' => function () {
    return redirect('/admin/log-viewer');
}]);
    //权限管理路由
    //Route::get('permission/{cid}/create', ['as' => 'admin.permission.create', 'uses' => 'PermissionController@create']);
    Route::get('permission/create', ['as' => 'permission.create', 'uses' => 'PermissionController@create']);
    Route::post('permission/store', ['as' => 'permission.store', 'uses' => 'PermissionController@store']);
    Route::get('permission/{cid?}', ['as' => 'admin.permission.index', 'uses' => 'PermissionController@index']);
    Route::post('permission/index', ['as' => 'admin.permission.index', 'uses' => 'PermissionController@index']); //查询
    Route::resource('permission', 'PermissionController', ['names' => ['edit' => 'admin.permission.edit', 'update' => 'admin.permission.update','store' => 'admin.permission.create','destroy'=>'admin.permission.destroy']]
    );


    //角色管理路由
    Route::get('role/index', ['as' => 'admin.role.index', 'uses' => 'RoleController@index']);
    Route::post('role/index', ['as' => 'admin.role.index', 'uses' => 'RoleController@index']);
    Route::resource('role', 'RoleController', ['names' => ['edit' => 'admin.role.edit', 'store' => 'admin.role.create']]);


    //用户管理路由
    Route::get('user/index', ['as' => 'admin.user.index', 'uses' => 'UserController@index']);  //用户管理
    Route::post('user/index', ['as' => 'admin.user.index', 'uses' => 'UserController@index']);
    Route::resource('user', 'UserController', ['names' => ['update' => 'admin.role.edit', 'store' => 'admin.role.create']]);

    //机构管理路由
    Route::match(['get','post'],'institution/index', ['as' => 'admin.institution.index', 'uses' => 'InstitutionController@index']);  //用户管理
    //Route::post('institution/index', ['as' => 'admin.institution.index', 'uses' => 'InstitutionController@index']);
    Route::resource('institution', 'InstitutionController', ['names' => ['update' => 'admin.institution.edit', 'store' => 'admin.institution.create']]);

    //机构类型管理路由
    Route::match(['get','post'],'kind/index', ['as' => 'admin.kind.index', 'uses' => 'KindController@index']);  //用户管理
    //Route::post('institution/index', ['as' => 'admin.institution.index', 'uses' => 'InstitutionController@index']);
    Route::resource('kind', 'KindController', ['names' => ['update' => 'admin.kind.edit', 'store' => 'admin.kind.create']]);
    
    
        //菜单管理路由
    Route::match(['get','post'],'menu/index', ['as' => 'admin.menu.index', 'uses' => 'MenuController@index']);  //用户管理
    //Route::post('institution/index', ['as' => 'admin.institution.index', 'uses' => 'InstitutionController@index']);
    Route::resource('menu', 'MenuController', ['names' => ['edit' => 'admin.menu.edit','update' => 'admin.menu.update','create' => 'admin.menu.create', 'destroy' => 'admin.menu.destroy']]);

});

Route::get('/', function () {
    return redirect('/admin/index');
});
