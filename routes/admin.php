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

    //Route::get('/', 'IndexController@Index');







//Route::group(['middleware' => ['auth:admin', 'check.permission','authAdmin']], function () {
    Route::group(['middleware' => ['auth:admin',  'authAdmin']], function () {
       
 Route::get('/dash','DashboardController@index');
 Route::get('/i18n', 'DashboardController@dataTableI18n');
 
 Route::get('/index', ['uses' => function () {
    return redirect('/admin/dash');
}]);
    Route::get('/', function () {
    return redirect('/admin/index');
});

    //权限管理路由
    //Route::get('permission/{cid}/create', ['as' => 'admin.permission.create', 'uses' => 'PermissionController@create']);
    //Route::get('permission/create', ['as' => 'permission.create', 'uses' => 'PermissionController@create']);
    //Route::post('permission/store', ['as' => 'permission.store', 'uses' => 'PermissionController@store']);
    //Route::get('permission/{cid?}', ['as' => 'admin.permission.index', 'uses' => 'PermissionController@index']);
    //Route::post('permission/index', ['as' => 'admin.permission.index', 'uses' => 'PermissionController@index']); //查询
    Route::resource('permission', 'PermissionController');



    //角色管理路由
    //Route::get('role/index', ['as' => 'admin.role.index', 'uses' => 'RoleController@index']);
    //Route::post('role/index', ['as' => 'admin.role.index', 'uses' => 'RoleController@index']);
    //Route::resource('role', 'RoleController', ['names' => ['edit' => 'admin.role.edit', 'store' => 'admin.role.create']]);
    Route::resource('role', 'RoleController');


    //用户管理路由
    //Route::get('user/index', ['as' => 'admin.user.index', 'uses' => 'UserController@index']);  //用户管理
    //Route::post('user/index', ['as' => 'admin.user.index', 'uses' => 'UserController@index']);
    //Route::resource('user', 'UserController', ['names' => ['update' => 'admin.role.edit', 'store' => 'admin.role.create']]);
    Route::resource('user', 'UserController');

    //机构管理路由
    //Route::match(['get','post'],'institution/index', ['as' => 'admin.institution.index', 'uses' => 'InstitutionController@index']);  //用户管理
    //Route::post('institution/index', ['as' => 'admin.institution.index', 'uses' => 'InstitutionController@index']);
    //Route::resource('institution', 'InstitutionController', ['names' => ['update' => 'admin.institution.edit', 'store' => 'admin.institution.create']]);
    Route::get('institution/clear','InstitutionController@cacheClear');
    Route::resource('institution', 'InstitutionController');

    //机构类型管理路由
    //Route::match(['get','post'],'kind/index', ['as' => 'admin.kind.index', 'uses' => 'KindController@index']);  //用户管理
    //Route::post('institution/index', ['as' => 'admin.institution.index', 'uses' => 'InstitutionController@index']);
    //Route::resource('kind', 'KindController', ['names' => ['update' => 'admin.kind.edit', 'store' => 'admin.kind.create']]);
    Route::resource('kind', 'KindController');
    
    Route::get('menu/clear','MenuController@cacheClear');

        //菜单管理路由
    //Route::match(['get','post'],'menu/index', ['as' => 'admin.menu.index', 'uses' => 'MenuController@index']);  //用户管理
    //Route::post('institution/index', ['as' => 'admin.institution.index', 'uses' => 'InstitutionController@index']);
    //Route::resource('menu', 'MenuController', ['names' => ['edit' => 'admin.menu.edit','update' => 'admin.menu.update','create' => 'admin.menu.create', 'destroy' => 'admin.menu.destroy']]);
    Route::resource('menu', 'MenuController');
    	// 菜单
    //机构类别
    Route::resource('kind', 'KindController');
    
    //员工
        Route::get('employee/autocreate', 'EmployeeController@autocreate');
        Route::post('employee/autostore', 'EmployeeController@autostore');
        Route::post('employee/autodelete', 'EmployeeController@autodelete');
        Route::get('employee/autodeleteview', 'EmployeeController@autodeleteview');
        Route::get('employee/downloadview', 'EmployeeController@downloadview');
        Route::post('employee/download', 'EmployeeController@download');
    Route::resource('employee', 'EmployeeController');
    
    //前台操作员表

    Route::post('fuser/getemployees','FuserController@getEmployees');
    Route::get('fuser/autocreate', 'FuserController@autocreate');
    Route::post('fuser/autostore', 'FuserController@autostore');
    Route::post('fuser/autodelete', 'FuserController@autodelete');
    Route::get('fuser/autodeleteview', 'FuserController@autodeleteview');
    Route::resource('fuser', 'FuserController');

    //设备品牌表
        Route::resource('brand', 'DeviceBrandController');

        //设备供应表
        Route::resource('supplier', 'DeviceSupplierController');

        //设备用途表
        Route::resource('equipment', 'DeviceEquipmentUseController');

        //设备分类表
        Route::resource('deviceclass', 'DeviceClassController');

        //设备型号表
        Route::resource('devicemodel', 'DeviceModelController');

        //设备保养厂商
        Route::resource('maintenanceprovider','MaintenanceProviderController');

        //根据品牌查 设备型号详情
        Route::post('devicewarehouse/ajaxbrand','DeviceWareHouseController@ajaxbrand');
        //设备批文详情
        Route::get('devicewarehouse/showfile','DeviceWareHouseController@showfile');

        //设备补录
        Route::get('devicewarehouse/supplement','DeviceWareHouseController@supplement');
        //设备申领
        Route::get('devicewarehouse/applytouse','DeviceWareHouseController@applytouse');
        //设备申领审核
        Route::get('devicewarehouse/applicationreview','DeviceWareHouseController@applicationreview');
        //设备调拔
        Route::get('devicewarehouse/redeployment','DeviceWareHouseController@redeployment');

        //设备借出
        Route::get('devicewarehouse/borrowing','DeviceWareHouseController@borrowing');

        //设备归还
        Route::get('devicewarehouse/equipmentreturn','DeviceWareHouseController@equipmentreturn');

        //设备仓库管理
        Route::resource('devicewarehouse','DeviceWareHouseController');


        //设备保管状态管理
        Route::resource('devicesavestate','DeviceSaveStateController');

        //设备工作状态管理
        Route::resource('deviceworkstate','DeviceWorkStateController');

        //设备仓库管理
        //Route::get('financialapproval/showfile/{financialapproval}','FinancialApprovalController@showfile')->name('financialapproval.showfile');

        //硬件购置审批文件管理
        Route::resource('financialapproval','FinancialApprovalController');


        //机构-硬件-配置表 管理
        Route::resource('houseinstitution','HouseInstitutionController');





});


