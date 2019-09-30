<?php

Route::group(['prefix' => '/list', 'as' => 'list.'], function () {
    Route::post('listing', 'ListController@listing')->name('listing');
    Route::post('add', 'ListController@add')->name('add');
    Route::post('edit', 'ListController@edit')->name('edit');
    Route::post('active', 'ListController@active')->name('active');
    Route::post('disable', 'ListController@disable')->name('disable');
    Route::post('export', 'ListController@export')->name('export');
    Route::post('import', 'ListController@import')->name('import');
});