<?php

Route::group(['prefix' => '/list', 'as' => 'list.', 'middleware' => 'role:' . \App\Models\Role::ROOT . '|' . \App\Models\Role::CC], function () {
    Route::post('listing', 'ListController@listing')->name('listing');
    Route::post('active','ListController@active')->name('active');
    Route::post('disable','ListController@disable')->name('disable');
    Route::post('add','ListController@add')->name('add');
    Route::post('edit','ListController@edit')->name('edit');
    Route::post('export', 'ListController@export')->name('export');
});