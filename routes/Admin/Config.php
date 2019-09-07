<?php
/**
 * Created by PhpStorm.
 * User: hungnm
 * Date: 7/11/2019
 * Time: 11:32 AM
 */

Route::group(['prefix' => '/configs', 'as' => 'configs.', 'middleware' => 'role:' . \App\Models\Role::ROOT . '|' . \App\Models\Role::ADMIN], function ()
{
    Route::post('listing', 'ConfigController@listing')->name('listing');
    Route::post('add', 'ConfigController@add')->name('add');
    Route::post('edit', 'ConfigController@edit')->name('edit');
});