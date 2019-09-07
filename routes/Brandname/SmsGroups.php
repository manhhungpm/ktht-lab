<?php

Route::group(['prefix' => '/sms-groups', 'as' => 'sms-groups.', 'middleware' => 'role:' . \App\Models\Role::ROOT . '|' . \App\Models\Role::CC], function () {
    Route::post('listing', 'SmsGroupController@listing')->name('listing');
    Route::post('add', 'SmsGroupController@add')->name('add');
    Route::post('edit', 'SmsGroupController@edit')->name('edit');
    Route::post('delete', 'SmsGroupController@delete')->name('delete');
    Route::post('active', 'SmsGroupController@active')->name('active');
    Route::post('disable', 'SmsGroupController@disable')->name('disable');
    Route::post('listing-all','SmsGroupController@listingAll')->name('listing-all');
});