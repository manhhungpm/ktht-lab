<?php

Route::group(['prefix' => '/brandname-segment-config', 'as' => 'brandname-segment-config.'], function () {
    Route::post('listing', 'BrandnameSegmentConfigController@listing')->name('listing');
    Route::post('add', 'BrandnameSegmentConfigController@add')->name('add');
    Route::post('edit', 'BrandnameSegmentConfigController@edit')->name('edit');
    Route::post('active', 'BrandnameSegmentConfigController@active')->name('active');
    Route::post('inactive', 'BrandnameSegmentConfigController@inactive')->name('inactive');
});
