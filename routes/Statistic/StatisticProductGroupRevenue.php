<?php

Route::group(['prefix' => '/product-group-revenue', 'as' => 'product-group-revenue.'], function () {
    Route::post('monthly-listing', 'StatisticProductGroupRevenueController@monthlyListing')->name('monthly-listing');
    Route::post('monthly-chart', 'StatisticProductGroupRevenueController@monthlyChart')->name('monthly-chart');
    Route::post('export', 'StatisticProductGroupRevenueController@export')->name('export');
    Route::post('monthly-detailed-listing', 'StatisticProviderRevenueController@monthlyDetailedListing')->name('monthly-detailed-listing');
});
