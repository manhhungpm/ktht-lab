<?php

Route::group(['prefix' => '/brandname-timeframe-config', 'as' => 'brandname-timeframe-config.'], function () {
    Route::post('listing', 'BrandnameTimeFrameConfigController@listing')->name('listing');
    Route::post('add', 'BrandnameTimeFrameConfigController@add')->name('add');
    Route::post('edit', 'BrandnameTimeFrameConfigController@edit')->name('edit');
    Route::post('active', 'BrandnameTimeFrameConfigController@active')->name('active');
    Route::post('inactive', 'BrandnameTimeFrameConfigController@inactive')->name('inactive');
//    Route::post('change-status', 'BrandnameDuplicateConfigController@changeStatus')->name('change-status');
});
