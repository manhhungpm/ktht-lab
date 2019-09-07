<?php

Route::group(['prefix' => '/config', 'as' => 'config.', 'middleware' => 'role:' . \App\Models\Role::ROOT . '|' . \App\Models\Role::CC], function () {
    Route::post('listing', 'ConfigController@listing')->name('listing');
    Route::post('active', 'ConfigController@active')->name('active');
    Route::post('disable', 'ConfigController@disable')->name('disable');
    Route::post('add', 'ConfigController@add')->name('add');
    Route::post('edit', 'ConfigController@edit')->name('edit');
    Route::post('select-v-broadcast','ConfigController@selectVBroadCast')->name('select-v-broadcast');
    Route::post('select-other-broadcast','ConfigController@selectOtherBroadCast')->name('select-other-broadcast');
});