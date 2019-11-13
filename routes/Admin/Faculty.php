<?php

Route::group(['prefix' => '/faculty', 'as' => 'faculty.', 'middleware' => 'role:' . \App\Models\Role::ROOT . '|' . \App\Models\Role::ADMIN], function () {
    Route::post('listing', 'FacultyController@listing')->name('listing');
    Route::post('add', 'FacultyController@add')->name('add');
    Route::post('edit', 'FacultyController@edit')->name('edit');
    Route::post('active', 'FacultyController@active')->name('active');
    Route::post('disable', 'FacultyController@disable')->name('disable');
    Route::post('listing-all', 'FacultyController@listingAll')->name('listing-all');
});