<?php

Route::group(['prefix' => '/widget', 'as' => 'widget.', 'middleware' => 'role:' . \App\Models\Role::ROOT
    . '|' . \App\Models\Role::ADMIN . '|' . \App\Models\Role::LEADER], function () {
    Route::post('get-data', 'WidgetController@getData')->name('get-data');
});