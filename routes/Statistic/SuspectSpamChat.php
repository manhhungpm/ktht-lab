<?php

Route::group(['prefix' => '/suspect-spam-chat', 'as' => 'suspect-spam-chat.'], function () {
    Route::post('get-data', 'SuspectSpamChatController@getData')->name('get-data');
});