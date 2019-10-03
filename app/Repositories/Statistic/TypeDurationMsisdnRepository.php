<?php

namespace App\Repositories\Statistic;

use App\Models\Statistic\TypeDurationMsisdn;
use App\Repositories\BaseRepository;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class TypeDurationMsisdnRepository extends BaseRepository
{
    public function model()
    {
        return TypeDurationMsisdn::class;
    }

    public function getData($filter)
    {
        $grid = [[]];
        $msisdnArr = [1, 2, 3, 4];
        $period = CarbonPeriod::create($filter['from'], $filter['to']);

        foreach ($msisdnArr as $msi) {
            foreach ($period as $dt) {
                $date = $dt->format('m/Y');
                $grid[$msi][$date] = 0;
            }
        }

//        var_dump($grid);

        foreach ($msisdnArr as $msi) {
            $query = $this->model->select('month', 'value')->where('msisdn_type_id', $msi)
                ->whereDate('month', '>=', $filter['from'])
                ->whereDate('month', '<=', $filter['to'])
                ->orderBy('month', 'asc')->get()->each(function ($obj) use (&$grid,$msi) {
                    $date = Carbon::createFromFormat('Y-m-d', $obj->month)->format('m/Y');
                    $grid[$msi][$date] = $obj->value;
                });
        }
        return $grid;
    }
}