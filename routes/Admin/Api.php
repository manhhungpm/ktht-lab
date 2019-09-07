<?php
/**
 * Created by PhpStorm.
 * User: hungnm
 * Date: 7/10/2019
 * Time: 8:14 AM
 */

Route::group(['prefix' => '/manage-api', 'as' => 'manage-api.', 'middleware' => 'role:' . \App\Models\Role::ROOT . '|' . \App\Models\Role::ADMIN], function () {
    Route::post('listing-api', 'ApiController@listing')->name('listing-api');
    Route::post('disable-api', 'ApiController@disable')->name('disable-api');
    Route::post('active-api', 'ApiController@active')->name('active-api');
    Route::post('add-api', 'ApiController@add')->name('add-api');
    Route::post('edit-api', 'ApiController@edit')->name('edit-api');

    Route::post('listing-account-api', 'ApiAccountController@listing')->name('listing-account-api');
    Route::post('disable-account-api', 'ApiAccountController@disable')->name('disable-account-api');
    Route::post('active-account-api', 'ApiAccountController@active')->name('active-account-api');
    Route::post('add-account-api', 'ApiAccountController@add')->name('add-account-api');
    Route::post('edit-account-api', 'ApiAccountController@edit')->name('edit-account-api');
    Route::post('reset-key', 'ApiAccountController@resetKey')->name('reset-key');
});