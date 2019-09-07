<?php

Route::group(['prefix' => '/report-day', 'as' => 'report-day.', 'middleware' => 'role:' . \App\Models\Role::ROOT . '|' . \App\Models\Role::CC], function () {
    Route::post('alias/listing', 'ReportDayController@listingAlias')->name('listing-alias');
    Route::post('alias/export', 'ReportDayController@exportAlias')->name('export-alias');
    Route::post('segment/listing', 'ReportDayController@listingSegment')->name('listing-segment');
    Route::post('segment/export', 'ReportDayController@exportSegment')->name('export-segment');
});