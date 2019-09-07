<?php

Route::group(['prefix' => '/report-duplicate', 'as' => 'report-duplicate.', 'middleware' => 'role:' . \App\Models\Role::ROOT . '|' . \App\Models\Role::CC], function () {
    Route::post('day/alias/listing', 'ReportDuplicateDayAliasController@listing')->name('listing');
    Route::post('day/alias/export', 'ReportDuplicateDayAliasController@export')->name('export');
    Route::post('day/segment/listing', 'ReportDuplicateDaySegmentController@listing')->name('listing');
    Route::post('day/segment/export', 'ReportDuplicateDaySegmentController@export')->name('export');

    Route::post('month/alias/listing','ReportDuplicateMonthAliasController@listing')->name('listing');
    Route::post('month/alias/export','ReportDuplicateMonthAliasController@export')->name('export');
    Route::post('month/segment/listing', 'ReportDuplicateMonthSegmentController@listing')->name('listing');
    Route::post('month/segment/export', 'ReportDuplicateMonthSegmentController@export')->name('export');
});