<?php

Route::group(['prefix' => '/msisdn-summary-type', 'as' => 'msisdn-summary-type.'], function () {
    Route::post('listing', 'MsisdnSummaryTypeController@listing')->name('listing');
    Route::post('get-total', 'MsisdnSummaryTypeController@getTotal')->name('get-total');
    Route::post('label','MsisdnSummaryTypeController@label')->name('label');
    Route::post('label-multiple','MsisdnSummaryTypeController@labelMultiple')->name('label-multiple');
});