<?php

Route::group(['prefix' => '/rent', 'as' => 'rent.', 'middleware' => 'role:' . \App\Models\Role::ROOT
    . '|' . \App\Models\Role::ADMIN. '|' . \App\Models\Role::LEADER. '|' . \App\Models\Role::USER. '|' . \App\Models\Role::STOCKER], function () {
    Route::post('listing', 'RentController@listing')->name('listing');
    Route::post('add', 'RentController@add')->name('add');
    Route::post('edit', 'RentController@edit')->name('edit');
    Route::post('export', 'RentController@export')->name('export');
    Route::post('pay', 'RentController@pay')->name('pay');
    Route::post('borrow', 'RentController@borrow')->name('borrow');
    Route::post('approved', 'RentController@approved')->name('approved');
    Route::post('deny', 'RentController@deny')->name('deny');
});
