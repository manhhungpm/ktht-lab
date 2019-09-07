<?php

Route::group(['prefix' => '/provider-revenue', 'as' => 'provider-revenue.'], function () {
    Route::post('monthly-listing', 'StatisticProviderRevenueController@monthlyListing')->name('monthly-listing');
    Route::post('monthly-chart', 'StatisticProviderRevenueController@monthlyChart')->name('monthly-chart');
    Route::post('export', 'StatisticProviderRevenueController@export')->name('export');
    Route::post('monthly-detailed-listing', 'StatisticProviderRevenueController@monthlyDetailedListing')->name('monthly-detailed-listing');
});
