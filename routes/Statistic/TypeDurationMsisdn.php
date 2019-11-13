<?php

Route::group(['prefix' => '/type-duration-msisdn', 'as' => 'type-duration-msisdn.'], function () {
    Route::post('get-data', 'TypeDurationMsisdnController@getData')->name('get-data');
});