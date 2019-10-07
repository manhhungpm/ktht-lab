<?php

Route::group(['prefix' => '/alias-block-spam', 'as' => 'alias-block-spam.'], function () {
    Route::post('listing', 'AliasBlockSpamController@listing')->name('listing');
});