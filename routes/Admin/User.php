<?php
/**
 * Created by PhpStorm.
 * User: hungnm
 * Date: 6/24/2019
 * Time: 4:23 PM
 */

Route::group(['prefix' => '/user', 'as' => 'user.', 'middleware' => 'role:' . \App\Models\Role::ROOT . '|' . \App\Models\Role::ADMIN], function () {
    Route::post('listing', 'UserController@listing')->name('listing');
    Route::post('disable', 'UserController@disable')->name('disable');
    Route::post('active', 'UserController@active')->name('active');
    Route::post('add', 'UserController@add')->name('add');
    Route::post('edit', 'UserController@edit')->name('edit');
    Route::post('listing-all', 'UserController@listingAll')->name('listing-all');
    Route::post('update-password', 'UserController@updatePassword')->name('update-password');
});
