<?php

Route::group(['prefix' => '/log', 'as' => 'log.', 'middleware' => 'role:' . \App\Models\Role::ROOT . '|' . \App\Models\Role::ADMIN], function () {
    Route::post('listing', 'LogController@listing')->name('listing');
});