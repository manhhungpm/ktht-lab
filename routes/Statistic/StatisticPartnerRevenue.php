<?php

Route::group(['prefix' => '/partner-revenue', 'as' => 'partner-revenue.'], function () {
    Route::post('monthly-listing', 'StatisticPartnerRevenueController@monthlyListing')->name('monthly-listing');
    Route::post('monthly-chart', 'StatisticPartnerRevenueController@monthlyChart')->name('monthly-chart');
    Route::post('export', 'StatisticPartnerRevenueController@export')->name('export');
    Route::post('last-12-months-chart', 'StatisticPartnerRevenueController@lastTwelveMonthsChart')->name('last-12-months-chart');
    Route::post('this-year-stats', 'StatisticPartnerRevenueController@thisYearStats')->name('this-year-stats');
    Route::post('this-year-top', 'StatisticPartnerRevenueController@thisYearTop')->name('this-year-top');
    Route::post('monthly-detailed-listing', 'StatisticPartnerRevenueController@monthlyDetailedListing')->name('monthly-detailed-listing');

});
