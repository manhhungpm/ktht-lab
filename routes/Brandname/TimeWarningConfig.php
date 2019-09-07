<?php

Route::group(['prefix' => '/brandname-time-warning-config', 'as' => 'brandname-time-warning-config.'], function () {
    Route::post('listing', 'BrandnameTimeWarningConfigController@listing')->name('listing');
    Route::post('add', 'BrandnameTimeWarningConfigController@add')->name('add');
    Route::post('edit', 'BrandnameTimeWarningConfigController@edit')->name('edit');
    Route::post('active', 'BrandnameTimeWarningConfigController@active')->name('active');
    Route::post('inactive', 'BrandnameTimeWarningConfigController@inactive')->name('inactive');
});