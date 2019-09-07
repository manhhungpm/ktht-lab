<?php

Route::group(['prefix' => '/brandname-time-warning-config-history', 'as' => 'brandname-time-warning-config-history.'], function () {
    Route::post('listing', 'BrandnameTimeWarningConfigHistoryController@listing')->name('listing');
});