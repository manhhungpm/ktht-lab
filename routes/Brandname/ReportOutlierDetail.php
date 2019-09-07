<?php

Route::group(['prefix' => '/report-outlier-detail', 'as' => 'report-outlier-detail.'], function () {
    Route::post('listing', 'ReportOutlierDetailController@listing')->name('listing');
});