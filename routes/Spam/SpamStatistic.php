<?php

Route::group(['prefix' => '/statistic', 'as' => 'statistic'], function () {
    Route::post('pattern', 'SpamStatisticController@getPatternStatistic')->name('pattern');
    Route::post('phone', 'SpamStatisticController@getPhoneStatistic')->name('phone');
});
