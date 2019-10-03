<?php

Route::group(['prefix' => '/list', 'as' => 'list.'], function () {
    Route::post('listing', 'BlackWhiteListController@listing')->name('listing');
    Route::post('add', 'BlackWhiteListController@add')->name('add');
    Route::post('edit', 'BlackWhiteListController@edit')->name('edit');
    Route::post('active', 'BlackWhiteListController@active')->name('active');
    Route::post('disable', 'BlackWhiteListController@disable')->name('disable');
    Route::post('export', 'BlackWhiteListController@export')->name('export');
    Route::post('import', 'BlackWhiteListController@import')->name('import');
});