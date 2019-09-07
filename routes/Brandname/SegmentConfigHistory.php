<?php

Route::group(['prefix' => '/brandname-segment-config-history', 'as' => 'brandname-segment-config-history.'], function () {
    Route::post('listing', 'BrandnameSegmentConfigHistoryController@listing')->name('listing');
});