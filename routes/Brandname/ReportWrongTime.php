<?php

Route::group(['prefix' => '/report-wrong-time', 'as' => 'report-wrong-time.'], function () {
    Route::post('listing', 'ReportWrongTimeController@listing')->name('listing');
});