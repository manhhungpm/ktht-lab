<?php

Route::group(['prefix' => '/report-month', 'as' => 'report-month.', 'middleware' => 'role:' . \App\Models\Role::ROOT . '|' . \App\Models\Role::CC], function () {
    Route::post('alias/listing', 'ReportMonthController@listingAlias')->name('listing-alias');
    Route::post('alias/export', 'ReportMonthController@exportAlias')->name('export-alias');
    Route::post('segment/listing', 'ReportMonthController@listingSegment')->name('listing-segment');
    Route::post('segment/export', 'ReportMonthController@exportSegment')->name('export-segment');
});