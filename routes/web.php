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
/*
Route::get('/', function () {
    return view('welcome');
});*/

Route::get('/', 'StaticController@home')->name('home');
Route::get('/help', 'StaticController@help')->name('help');
Route::get('/about', 'StaticController@about')->name('about');

//注册路由
Route::get('register', "UsersController@create")->name('register');

//用户模型路由
Route::resource('users', 'UsersController');

//登陆登出路由
Route::get('login', 'SessionController@create')->name('login');
Route::post('login', 'SessionController@store')->name('login');
Route::get('logout', 'SessionController@destory')->name('logout');

