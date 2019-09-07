<?php

Route::group(['prefix' => '/product-group-quantity', 'as' => 'product-group-quantity.'], function () {
    Route::post('daily-listing', 'StatisticProductGroupQuantityController@dailyListing')->name('daily-listing');
    Route::post('daily-detailed-listing', 'StatisticProductGroupQuantityController@dailyDetailedListing')->name('daily-detailed-listing');
    Route::post('monthly-detailed-listing', 'StatisticProductGroupQuantityController@monthlyDetailedListing')->name('monthly-detailed-listing');
    Route::post('daily-chart', 'StatisticProductGroupQuantityController@dailyChart')->name('daily-chart');
    Route::post('monthly-listing', 'StatisticProductGroupQuantityController@monthlyListing')->name('monthly-listing');
    Route::post('monthly-chart', 'StatisticProductGroupQuantityController@monthlyChart')->name('monthly-chart');
    Route::post('this-month-stats', 'StatisticProductGroupQuantityController@thisMonthStats')->name('this-month-stats');
    Route::post('last-12-months-chart', 'StatisticProductGroupQuantityController@lastTwelveMonthsChart')->name('last-12-months-chart');
    Route::post('export', 'StatisticProductGroupQuantityController@export')->name('export');
});
