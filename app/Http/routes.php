<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return redirect()->guest('/login');
});

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => 'web'], function () {
    Route::auth();
});
Route::group(['middleware' => 'web','prefix' => 'admin'], function () {
    Route::get('/', 'DashboardController@index');
    Route::get('connection', 'ConnectionController@index');
    Route::get('testing', 'ConnectionController@test');
    Route::get('testing1', 'ConnectionController@testt');
    Route::post('connection', 'ConnectionController@create');
    Route::get('mysqlconnection', 'ConnectionController@mysql');
    Route::post('mysqlconnection', 'ConnectionController@mysqlcreate');
});
