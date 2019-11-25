<?php

Route::group(['prefix' => '/dashboard', 'as' => 'dashboard.', 'middleware' => 'role:' . \App\Models\Role::ROOT . '|' . \App\Models\Role::ADMIN], function () {
//    Route::post('spam-warning', 'DashboardController@spamWarning')->name('spam-warning')->middleware('role:'.\App\Models\Role::CC);
//    Route::post('a2p-warning', 'DashboardController@a2pWarning')->name('a2p-warning')->middleware('role:'.\App\Models\Role::A2P);
//    Route::post('listing', 'DashboardController@listing')->name('listing');
});