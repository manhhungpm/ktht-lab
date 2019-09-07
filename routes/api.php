<?php

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

Route::post('auth/login', 'AuthController@login')->name('login');



Route::group([
    'middleware' => ['api', 'auth:api','role:admin|user']
], function () {

    includeRouteFiles(__DIR__ . '/Auth/');
    includeRouteFiles(__DIR__ . '/Dashboard/');

    Route::group(['namespace' => 'Admin', 'prefix' => '/admin', 'as' => 'admin.','middleware' => []], function () {
        includeRouteFiles(__DIR__ . '/Admin/');
    });

    Route::group(['namespace' => 'Brandname', 'prefix' => '/brandname', 'as' => 'brandname.','middleware' => []], function () {
        includeRouteFiles(__DIR__ . '/Brandname/');
    });

});

Route::group(['namespace' => 'Spam', 'prefix' => '/spam', 'as' => 'spam.', 'middleware' => ['api', 'auth:api']], function () {
    includeRouteFiles(__DIR__ . '/Spam/');
});