<?php

Route::group(['prefix' => '/provider', 'as' => 'provider.', 'middleware' => 'role:' . \App\Models\Role::ROOT . '|' . \App\Models\Role::ADMIN], function () {
    Route::post('listing', 'ProviderController@listing')->name('listing');
    Route::post('add', 'ProviderController@add')->name('add');
    Route::post('edit', 'ProviderController@edit')->name('edit');
    Route::post('active', 'ProviderController@active')->name('active');
    Route::post('disable', 'ProviderController@disable')->name('disable');
    Route::post('listing-all', 'ProviderController@listingAll')->name('listing-all');
});