<?php

Route::group(['prefix' => '/brandname-timeframe-config-history', 'as' => 'brandname-timeframe-config-history.'], function () {
    Route::post('listing', 'BrandnameTimeFrameConfigHistoryController@listing')->name('listing');
});