<?php

Route::group(['prefix' => '/brandname-segment-warning-config-history', 'as' => 'brandname-segment-warning-config-history.'], function () {
    Route::post('listing', 'BrandnameSegmentWarningConfigHistoryController@listing')->name('listing');
});
