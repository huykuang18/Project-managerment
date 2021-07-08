<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['namespace' => 'App\Http\Controllers',],function(){
    Route::post('register', 'UserController@register');
    Route::post('login', 'UserController@login');
    Route::get('users', 'UserController@allUser');
});

Route::group(['middleware' => 'jwt.auth', 'namespace' => 'App\Http\Controllers'], function () {
    Route::get('user-info', 'UserController@getUserInfo');
    Route::get('logout', 'UserController@logout');
});
