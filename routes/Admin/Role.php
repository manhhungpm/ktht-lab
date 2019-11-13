<?php
/**
 * Created by PhpStorm.
 * User: hungnm
 * Date: 6/29/2019
 * Time: 3:01 PM
 */

Route::group(['prefix' => '/role', 'as' => 'role.', 'middleware' => 'role:' . \App\Models\Role::ROOT . '|' . \App\Models\Role::ADMIN], function () {
    Route::post('listing', 'RoleController@listing')->name('listing');
    Route::post('add', 'RoleController@add')->name('add');
    Route::post('edit', 'RoleController@edit')->name('edit');
    Route::post('active', 'RoleController@active')->name('active');
    Route::post('disable', 'RoleController@disable')->name('disable');
    Route::post('listing-all', 'RoleController@listingAll')->name('listing-all');
});