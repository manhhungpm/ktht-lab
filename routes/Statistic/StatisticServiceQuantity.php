<?php

Route::group(['prefix' => '/service-quantity', 'as' => 'service-quantity.'], function () {
    Route::post('daily-listing', 'StatisticServiceQuantityController@dailyListing')->name('daily-listing');
    Route::post('daily-chart', 'StatisticServiceQuantityController@dailyChart')->name('daily-chart');
    Route::post('monthly-listing', 'StatisticServiceQuantityController@monthlyListing')->name('monthly-listing');
    Route::post('monthly-chart', 'StatisticServiceQuantityController@monthlyChart')->name('monthly-chart');
    Route::post('this-year-stats', 'StatisticServiceQuantityController@thisYearStats')->name('this-year-stats');
    Route::post('last-12-months-chart', 'StatisticServiceQuantityController@lastTwelveMonthsChart')->name('last-12-months-chart');
    Route::post('export', 'StatisticServiceQuantityController@export')->name('export');
});
