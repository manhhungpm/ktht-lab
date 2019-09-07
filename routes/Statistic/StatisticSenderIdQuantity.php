<?php

Route::group(['prefix' => '/sender-id-quantity', 'as' => 'sender-id-quantity.'], function () {
    Route::post('daily-listing', 'StatisticSenderIdQuantityController@dailyListing')->name('daily-listing');
    Route::post('daily-detailed-listing', 'StatisticSenderIdQuantityController@dailyDetailedListing')->name('daily-detailed-listing');
    Route::post('monthly-detailed-listing', 'StatisticSenderIdQuantityController@monthlyDetailedListing')->name('monthly-detailed-listing');
    Route::post('daily-chart', 'StatisticSenderIdQuantityController@dailyChart')->name('daily-chart');
    Route::post('monthly-listing', 'StatisticSenderIdQuantityController@monthlyListing')->name('monthly-listing');
    Route::post('monthly-chart', 'StatisticSenderIdQuantityController@monthlyChart')->name('monthly-chart');
    Route::post('this-year-stats', 'StatisticSenderIdQuantityController@thisYearStats')->name('this-year-stats');
    Route::post('last-12-months-chart', 'StatisticSenderIdQuantityController@lastTwelveMonthsChart')->name('last-12-months-chart');
    Route::post('export', 'StatisticSenderIdQuantityController@export')->name('export');
    Route::post('time-quantity-chart', 'StatisticSenderIdQuantityController@timeStatistic')->name('time-quantity-chart');
});
