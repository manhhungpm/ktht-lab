<?php
/**
 * Created by PhpStorm.
 * User: hungnm
 * Date: 7/12/2019
 * Time: 8:27 AM
 */
Route::group(['prefix' => '/smstopics', 'as' => 'smstopics.', 'middleware' => 'role:' . \App\Models\Role::ROOT . '|' . \App\Models\Role::ADMIN], function () {
    Route::post('listing', 'SmsTopicController@listing')->name('listing');
});