<?php

Route::group(['prefix' => '/brandname-segment-warning-config', 'as' => 'brandname-segment-warning-config.'], function () {
    Route::post('listing', 'BrandnameSegmentWarningConfigController@listing')->name('listing');
    Route::post('listing-sms-type', 'BrandnameSegmentWarningConfigController@listingSmsType')->name('listing-sms-type');
    Route::post('add', 'BrandnameSegmentWarningConfigController@add')->name('add');
    Route::post('edit', 'BrandnameSegmentWarningConfigController@edit')->name('edit');
    Route::post('active', 'BrandnameSegmentWarningConfigController@active')->name('active');
    Route::post('inactive', 'BrandnameSegmentWarningConfigController@inactive')->name('inactive');
    Route::post('change-status', 'BrandnameSegmentWarningConfigController@changeStatus')->name('change-status');
});
