<?php

Route::group(['prefix' => '/spam-patterns-warning', 'as' => 'spam-patterns-warning.'], function () {
    Route::post('listing', 'SpamPatternsWarningController@listing')->name('listing');
    Route::post('tag', 'SpamPatternsWarningController@tag')->name('tag');
    Route::post('ignore', 'SpamPatternsWarningController@ignore')->name('ignore');
    Route::post('list-all', 'SpamPatternsWarningController@listAll')->name('list-all');
    Route::post('export', 'SpamPatternsWarningController@export')->name('export');
    Route::post('list-all-source', 'SpamPatternsWarningController@listAllSource')->name('list-all-source');

});
