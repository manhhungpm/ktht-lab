<?php

Route::group(['prefix' => '/manager', 'as' => 'manager.'], function () {
    Route::post('listing', 'ManagerController@listing')->name('listing');
    Route::post('disable','ManagerController@disable')->name('disable');
    Route::post('active','ManagerController@active')->name('active');
    Route::post('add','ManagerController@add')->name('add');
    Route::post('edit','ManagerController@edit')->name('edit');
    Route::post('listing-all','ManagerController@listingAll')->name('listing-all');
});
