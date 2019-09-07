<?php

Route::group(['prefix' => '/report-accumulate', 'as' => 'report-accumulate-day.', 'middleware' => 'role:' . \App\Models\Role::ROOT . '|' . \App\Models\Role::CC], function () {
    Route::post('alias/listing', 'ReportAccumulateDayAliasController@listingAlias')->name('listing-alias');
    Route::post('alias/export', 'ReportAccumulateDayAliasController@exportAlias')->name('export-alias');
    Route::post('segment/listing', 'ReportAccumulateDaySegmentController@listing')->name('listing');
    Route::post('segment/export', 'ReportAccumulateDaySegmentController@export')->name('export');
});