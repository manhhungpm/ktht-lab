<?php

Route::group(['prefix' => '/spam-call-detail', 'as' => 'spam-call-detail.'], function () {
    Route::post('listing', 'SpamCallDetailController@listing')->name('listing');
    Route::post('get-total', 'SpamCallDetailController@getTotal')->name('get-total');
    Route::post('label','SpamCallDetailController@label')->name('label');
    Route::post('label-multiple','SpamCallDetailController@labelMultiple')->name('label-multiple');
});