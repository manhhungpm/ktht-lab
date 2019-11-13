<?php

Route::group(['prefix' => '/class', 'as' => 'class.', 'middleware' => 'role:' . \App\Models\Role::ROOT . '|' . \App\Models\Role::ADMIN], function () {
    Route::post('listing', 'ClassController@listing')->name('listing');
    Route::post('add', 'ClassController@add')->name('add');
    Route::post('edit', 'ClassController@edit')->name('edit');
    Route::post('active', 'ClassController@active')->name('active');
    Route::post('disable', 'ClassController@disable')->name('disable');
    Route::post('listing-all', 'ClassController@listingAll')->name('listing-all');
});