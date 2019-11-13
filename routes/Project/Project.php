<?php
/**
 * Created by PhpStorm.
 * User: manhhungpm
 * Date: 09/11/2019
 * Time: 23:59
 */

Route::group(['prefix' => '/project', 'as' => 'project.', 'middleware' => 'role:' . \App\Models\Role::ROOT . '|' . \App\Models\Role::ADMIN], function () {
    Route::post('listing', 'ProjectController@listing')->name('listing');
    Route::post('add', 'ProjectController@add')->name('add');
    Route::post('edit', 'ProjectController@edit')->name('edit');
    Route::post('active', 'ProjectController@active')->name('active');
    Route::post('disable', 'ProjectController@disable')->name('disable');
    Route::post('listing-all', 'ProjectController@listingAll')->name('listing-all');
});