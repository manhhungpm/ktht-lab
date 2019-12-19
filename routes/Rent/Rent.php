<?php

Route::group(['prefix' => '/rent', 'as' => 'rent.', 'middleware' => 'role:' . \App\Models\Role::ROOT
    . '|' . \App\Models\Role::ADMIN. '|' . \App\Models\Role::LEADER. '|' . \App\Models\Role::USER], function () {
    Route::post('listing', 'RentController@listing')->name('listing');
    Route::post('add', 'RentController@add')->name('add');
    Route::post('edit', 'RentController@edit')->name('edit');
    Route::post('active', 'RentController@active')->name('active');
    Route::post('disable', 'RentController@disable')->name('disable');
    Route::post('export', 'RentController@export')->name('export');
});