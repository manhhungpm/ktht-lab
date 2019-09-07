<?php

Route::group(['prefix' => '/provider-quantity', 'as' => 'provider-quantity.'], function () {
    Route::post('daily-listing', 'StatisticProviderQuantityController@dailyListing')->name('daily-listing');
    Route::post('daily-detailed-listing', 'StatisticProviderQuantityController@dailyDetailedListing')->name('daily-detailed-listing');
    Route::post('monthly-detailed-listing', 'StatisticProviderQuantityController@monthlyDetailedListing')->name('monthly-detailed-listing');
    Route::post('daily-chart', 'StatisticProviderQuantityController@dailyChart')->name('daily-chart');
    Route::post('monthly-listing', 'StatisticProviderQuantityController@monthlyListing')->name('monthly-listing');
    Route::post('monthly-chart', 'StatisticProviderQuantityController@monthlyChart')->name('monthly-chart');
    Route::post('this-month-stats', 'StatisticProviderQuantityController@thisMonthStats')->name('this-month-stats');
    Route::post('export', 'StatisticProviderQuantityController@export')->name('export');
});
