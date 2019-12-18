<?php
/**
 * Created by PhpStorm.
 * User: manhhungpm
 * Date: 02/11/2019
 * Time: 22:00
 */

Route::group(['prefix' => '/device-group', 'as' => 'device-group.', 'middleware' => 'role:' . \App\Models\Role::ROOT . '|' . \App\Models\Role::ADMIN], function () {
    Route::post('listing', 'DeviceGroupController@listing')->name('listing');
    Route::post('add', 'DeviceGroupController@add')->name('add');
    Route::post('edit', 'DeviceGroupController@edit')->name('edit');
    Route::post('active', 'DeviceGroupController@active')->name('active');
    Route::post('disable', 'DeviceGroupController@disable')->name('disable');
    Route::post('listing-all', 'DeviceGroupController@listingAll')->name('listing-all');
    Route::post('export', 'DeviceGroupController@export')->name('export');
});