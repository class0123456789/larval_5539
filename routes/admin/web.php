<?php

Route::group(['prefix'=>'admin','namespace'=>'Admin'],function(){
    //Route::match(['get','post'],'/login','EntryController@login');
    //Route::get('/login','AdminLoginController@index');
    //Route::post('/login', 'AdminLoginController@login')->name('login');
    //Route::get('/logout', 'AdminLoginController@logout');
    
    
    Route::get('/login', 'LoginController@showLoginForm')->name('admin.login');
    Route::post('/login', 'LoginController@login');
    Route::get('/logout', 'LoginController@logout');
    Route::post('/logout', 'LoginController@logout');
    
    
    //Route::group(['middleware' => ['auth:admin', 'check.permission']], function () {
    Route::group(['middleware' => ['auth:admin', 'authAdmin']], function () {
       
        Route::get('/dash','DashboardController@index');
        Route::get('/i18n', 'DashboardController@dataTableI18n');
 
        Route::get('/index', function () {
            return redirect('/admin/log-viewer');
        });
    });
    
});
