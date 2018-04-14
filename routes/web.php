<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});

//Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');

Route::get('/', 'HomeController@index')->middleware('auth');
Route::get('/home', 'HomeController@index')->name('home')->middleware('auth');

Route::get('/index', function () {
    return redirect('/home');
})->middleware('auth');

// room
Route::group(['middleware' => 'auth', 'prefix' => 'room'], function () {
    Route::get('create', 'RoomController@create');
    Route::get('lists', 'RoomController@lists');
    Route::post('add', 'RoomController@add');
    Route::get('/{id}/edit', 'RoomController@edit');
    Route::post('/{id}/update', 'RoomController@update');
    Route::get('{id}', 'RoomController@chat');
    Route::post("/{id}/join", 'RoomController@join');
});
Route::group(['middleware' => 'auth', 'prefix' => 'api/room' , 'namespace' => 'Api'], function () {
    Route::post("/{id}/join" , 'RoomController@join');
});
Auth::routes();

Route::any('/component/upload','Component\UploadController@upload');
Route::any('/component/delete','Component\UploadController@delete');
Route::any('/component/downloadfile','Component\UploadController@downloadfile');

Route::any('/component/filesLists','Component\UploadController@filesLists');
//Route::get('/home', 'HomeController@index')->name('home');
//include __DIR__.'/admin/web.php';
