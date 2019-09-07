<?php

Route::group(['prefix' => '/sender-id-revenue', 'as' => 'sender-id-revenue.'], function () {
    Route::post('monthly-listing', 'StatisticSenderIdRevenueController@monthlyListing')->name('monthly-listing');
    Route::post('monthly-chart', 'StatisticSenderIdRevenueController@monthlyChart')->name('monthly-chart');
    Route::post('export', 'StatisticSenderIdRevenueController@export')->name('export');

});
