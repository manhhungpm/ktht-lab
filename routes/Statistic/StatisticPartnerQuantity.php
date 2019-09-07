<?php

Route::group(['prefix' => '/partner-quantity', 'as' => 'partner-quantity.'], function () {
    Route::post('daily-listing', 'StatisticPartnerQuantityController@dailyListing')->name('daily-listing');
    Route::post('daily-detailed-listing', 'StatisticPartnerQuantityController@dailyDetailedListing')->name('daily-detailed-listing');
    Route::post('monthly-detailed-listing', 'StatisticPartnerQuantityController@monthlyDetailedListing')->name('monthly-detailed-listing');
    Route::post('daily-chart', 'StatisticPartnerQuantityController@dailyChart')->name('daily-chart');
    Route::post('monthly-listing', 'StatisticPartnerQuantityController@monthlyListing')->name('monthly-listing');
    Route::post('monthly-chart', 'StatisticPartnerQuantityController@monthlyChart')->name('monthly-chart');
    Route::post('this-month-stats', 'StatisticPartnerQuantityController@thisMonthStats')->name('this-month-stats');
    Route::post('last-12-months-chart', 'StatisticPartnerQuantityController@lastTwelveMonthsChart')->name('last-12-months-chart');
    Route::post('export', 'StatisticPartnerQuantityController@export')->name('export');
});
