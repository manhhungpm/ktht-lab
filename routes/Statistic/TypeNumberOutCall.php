<?php

Route::group(['prefix' => '/type-number-out-call', 'as' => 'type-number-out-call.'], function () {
    Route::post('get-data', 'TypeNumberOutCallController@getData')->name('get-data');
});