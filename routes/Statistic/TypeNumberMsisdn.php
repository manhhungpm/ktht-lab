<?php

Route::group(['prefix' => '/type-number-msisdn', 'as' => 'type-number-msisdn.'], function () {
    Route::post('get-data', 'TypeNumberMsisdnController@getData')->name('get-data');
});