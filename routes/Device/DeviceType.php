<?php
/**
 * Created by PhpStorm.
 * User: manhhungpm
 * Date: 05/11/2019
 * Time: 22:56
 */

Route::group(['prefix' => '/device-type', 'as' => 'device-type.', 'middleware' => 'role:' . \App\Models\Role::ROOT . '|' . \App\Models\Role::ADMIN], function () {
    Route::post('listing', 'DeviceTypeController@listing')->name('listing');
    Route::post('add', 'DeviceTypeController@add')->name('add');
    Route::post('edit', 'DeviceTypeController@edit')->name('edit');
    Route::post('active', 'DeviceTypeController@active')->name('active');
    Route::post('disable', 'DeviceTypeController@disable')->name('disable');
    Route::post('listing-all', 'DeviceTypeController@listingAll')->name('listing-all');
});