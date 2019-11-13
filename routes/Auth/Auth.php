<?php

Route::group(['prefix' => '/auth', 'as' => 'auth.'], function () {
    Route::post('logout', 'AuthController@logout')->name('logout');
    Route::post('refresh', 'AuthController@refresh')->name('refresh');
    Route::post('me', 'AuthController@me')->name('me');
});