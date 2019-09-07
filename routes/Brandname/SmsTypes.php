<?php

Route::group(['prefix' => '/sms-types', 'as' => 'sms-types.', 'middleware' => 'role:' . \App\Models\Role::ROOT . '|' . \App\Models\Role::CC], function () {
    Route::post('listing', 'SmsTypeController@listing')->name('listing');
    Route::post('add', 'SmsTypeController@add')->name('add');
    Route::post('edit', 'SmsTypeController@edit')->name('edit');
    Route::post('delete', 'SmsTypeController@delete')->name('delete');
    Route::post('active', 'SmsTypeController@active')->name('active');
    Route::post('disable', 'SmsTypeController@disable')->name('disable');
    Route::post('listing-all', 'SmsTypeController@listingAll')->name('listing-all');
});