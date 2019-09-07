<?php

Route::group(['prefix' => '/report-detail-request', 'as' => 'report-detail-request.', 'middleware' => 'role:' . \App\Models\Role::ROOT . '|' . \App\Models\Role::CC], function () {
    Route::post('listing', 'ReportDetailRequestController@listing')->name('listing');
    Route::post('resend', 'ReportDetailRequestController@resend')->name('resend');
    Route::post('add', 'ReportDetailRequestController@add')->name('add');
    Route::post('download', 'ReportDetailRequestController@download')->name('download');
});