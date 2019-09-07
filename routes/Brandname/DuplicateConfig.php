<?php

Route::group(['prefix' => '/brandname-duplicate-config', 'as' => 'brandname-duplicate-config.'], function () {
    Route::post('listing', 'BrandnameDuplicateConfigController@listing')->name('listing');
    Route::post('add', 'BrandnameDuplicateConfigController@add')->name('add');
    Route::post('edit', 'BrandnameDuplicateConfigController@edit')->name('edit');
    Route::post('active', 'BrandnameDuplicateConfigController@active')->name('active');
    Route::post('inactive', 'BrandnameDuplicateConfigController@inactive')->name('inactive');
});
