<?php

Route::group(['prefix' => '/statistic', 'as' => 'statistic.', 'middleware' => 'role:' . \App\Models\Role::ROOT
    . '|' . \App\Models\Role::ADMIN], function () {
    Route::post('get-data-pie', 'StatisticController@getDataDashboardPie')->name('get-data-pie');
    Route::post('get-data-column', 'StatisticController@getDataDashboardColumn')->name('get-data-column');
    Route::post('get-data-pie-project', 'StatisticController@getDataStatsPie')->name('get-data-pie-project');
    Route::post('get-data-column-user-device', 'StatisticController@getDataStatsColumn')->name('get-data-column-user-device');
});