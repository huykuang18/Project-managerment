<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
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
Route::get('/greeting/{locale}', function ($locale) {
    if (! in_array($locale, ['en', 'vi'])) {
    abort(400);
    }
    
    App::setLocale($locale);

    });

Route::group(['namespace' => 'App\Http\Controllers',],function(){
    /**User */
    Route::post('login', 'UserController@login');
    Route::get('users/all', 'UserController@getUserOptions');
    Route::apiResource('users', 'UserController');

    /**Project */
    Route::apiResource('projects', 'ProjectController');
    Route::apiResource('projects.members', 'MemberController',['except' => ['destroy']])->shallow();
    Route::delete('projects/{project}/members/{member}', 'MemberController@remove');
    Route::get('member/options', 'IssueController@getMemberOptions');

    /**Item */
    Route::apiResource('items', 'ItemController');

    /**Issue */
    Route::apiResource('issues', 'IssueController');

    /**Document */
    Route::apiResource('documents', 'DocumentController');
});

Route::group(['middleware' => 'jwt.auth', 'namespace' => 'App\Http\Controllers'], function () {
    Route::get('user-info', 'UserController@getUserInfo');
    Route::get('logout', 'UserController@logout');
});
