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
    includeRouteFiles(__DIR__ . '/Common/');

    Route::group(['namespace' => 'Admin', 'prefix' => '/admin', 'as' => 'admin.','middleware' => []], function () {
        includeRouteFiles(__DIR__ . '/Admin/');
    });

    Route::group(['namespace' => 'BlackWhite', 'prefix' => '/blackwhite', 'as' => 'blackwhite.','middleware' => []], function () {
        includeRouteFiles(__DIR__ . '/BlackWhite/');
    });

    Route::group(['namespace' => 'Statistic', 'prefix' => '/statistic', 'as' => 'statistic.','middleware' => []], function () {
        includeRouteFiles(__DIR__ . '/Statistic/');
    });

    Route::group(['namespace' => 'AliasBlockSpam','middleware' => []], function () {
        includeRouteFiles(__DIR__ . '/AliasBlockSpam/');
    });

    Route::group(['namespace' => 'SpamCallDetail','middleware' => []], function () {
        includeRouteFiles(__DIR__ . '/SpamCallDetail/');
    });

});