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
Route::get('auth/sso-login', 'AuthController@ssoLogin')->name('sso-login');
Route::get('auth/sso-logout', 'AuthController@ssoLogout')->name('sso-logout');
Route::get('{path}', function () {
    return view('index');
//})->where('path', '^((?!auth/sso-login).)*$')
})->where('path', '^((?!auth\sso-log(in|out)).)*$')
//    ->where('path','<>','auth/sso-login')
;
