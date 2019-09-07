<?php
/**
 * Created by PhpStorm.
 * User: hungnm
 * Date: 6/29/2019
 * Time: 3:01 PM
 */

Route::group(['prefix' => '/role', 'as' => 'role.', 'middleware' => 'role:' . \App\Models\Role::ROOT . '|' . \App\Models\Role::ADMIN], function () {
    Route::post('listing', 'RoleController@listing')->name('listing');
});