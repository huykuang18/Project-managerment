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
    /**User */
    Route::post('register', 'UserController@register');
    Route::put('user/update/{id}', 'UserController@update');
    Route::delete('user/delete/{id}', 'UserController@delete');
    Route::post('login', 'UserController@login');
    Route::get('users', 'UserController@allUser');

    /**Project */
    Route::get('projects', 'ProjectController@getProject');
    Route::post('project/create', 'ProjectController@createProject');
    Route::put('project/update', 'ProjectController@update');
    Route::delete('project/delete/{id}', 'ProjectController@delete');
    Route::get('project/member', 'ProjectController@getMember');
    Route::post('project/member/add', 'ProjectController@addMember');
    Route::delete('project/member/delete/{id}', 'ProjectController@removeMember');
});

Route::group(['middleware' => 'jwt.auth', 'namespace' => 'App\Http\Controllers'], function () {
    Route::get('user-info', 'UserController@getUserInfo');
    Route::get('logout', 'UserController@logout');
});
