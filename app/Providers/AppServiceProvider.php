<?php

namespace App\Providers;

use App\Exports\Concerns\WithCustomProperties;
use App\Exports\Concerns\WithCustomPropertiesHandler;
use App\Exports\Concerns\WithCustomPropertiesHandlerHasFilter;
use App\Exports\Concerns\WithCustomPropertiesHasFilter;
use Illuminate\Support\ServiceProvider;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Sheet;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        Sheet::macro('styleCells', function (Sheet $sheet, string $cellRange, array $style) {
            $sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style);
        });

        Sheet::macro('setURL', function (Sheet $sheet, string $cell, string $url) {
            $sheet->getCell($cell)->getHyperlink()->setUrl($url);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Excel::extend(WithCustomProperties::class, new WithCustomPropertiesHandler, AfterSheet::class);
        Excel::extend(WithCustomPropertiesHasFilter::class, new WithCustomPropertiesHandlerHasFilter, AfterSheet::class);
    }
}
