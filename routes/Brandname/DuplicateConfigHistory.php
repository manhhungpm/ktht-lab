<?php

Route::group(['prefix' => '/brandname-duplicate-config-history', 'as' => 'brandname-duplicate-config-history.'], function () {
    Route::post('listing', 'BrandnameDuplicateConfigHistoryController@listing')->name('listing');
});