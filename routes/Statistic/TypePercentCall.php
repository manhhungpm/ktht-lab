<?php

Route::group(['prefix' => '/type-percent-call', 'as' => 'type-percent-call.'], function () {
    Route::post('get-data', 'TypePercentCallController@getData')->name('get-data');
});