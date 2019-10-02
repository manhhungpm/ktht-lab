<?php

Route::group(['prefix' => '/msisdn-summary-type', 'as' => 'msisdn-summary-type.'], function () {
    Route::post('listing', 'MsisdnSummaryTypeController@listing')->name('listing');
});