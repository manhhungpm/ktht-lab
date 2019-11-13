<?php

Route::group(['prefix' => '/type-duration-type', 'as' => 'type-duration-type.'], function () {
    Route::post('get-data', 'TypeDurationTypeController@getData')->name('get-data');
});