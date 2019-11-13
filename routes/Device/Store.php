<?php
/**
 * Created by PhpStorm.
 * User: manhhungpm
 * Date: 04/11/2019
 * Time: 22:14
 */

Route::group(['prefix' => '/store', 'as' => 'store.', 'middleware' => 'role:' . \App\Models\Role::ROOT . '|' . \App\Models\Role::ADMIN], function () {
    Route::post('listing', 'StoreController@listing')->name('listing');
    Route::post('add', 'StoreController@add')->name('add');
    Route::post('edit', 'StoreController@edit')->name('edit');
    Route::post('active', 'StoreController@active')->name('active');
    Route::post('disable', 'StoreController@disable')->name('disable');
    Route::post('listing-all', 'StoreController@listingAll')->name('listing-all');
});