<?php

Route::group(['prefix' => '/spam-patterns-labeled', 'as' => 'spam-patterns-labeled.'], function () {
    Route::post('listing', 'SpamPatternsLabeledController@listing')->name('listing');
    Route::post('tag', 'SpamPatternsLabeledController@tag')->name('tag');
    Route::post('add', 'SpamPatternsLabeledController@add')->name('add');
    Route::post('ignore', 'SpamPatternsLabeledController@ignore')->name('ignore');
    Route::post('list-all', 'SpamPatternsLabeledController@listAll')->name('list-all');
    Route::post('export', 'SpamPatternsLabeledController@export')->name('export');
    Route::post('list-all-source', 'SpamPatternsLabeledController@listAllSource')->name('list-all-source');

});
