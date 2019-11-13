<?php

Route::group(['prefix' => '/media', 'as' => 'media.'], function () {
    Route::post('upload', 'MediaController@uploadFile')->name('upload');
});