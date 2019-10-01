<?php

namespace App\Repositories\Statistic;

use App\Models\Statistic\SuspectSpamChart;
use App\Repositories\BaseRepository;
use Carbon\Carbon;
use Carbon\CarbonPeriod;


class SuspectSpamChatRepository extends BaseRepository
{
    /**
     * @return string
     */
    public function model()
    {
        return SuspectSpamChart::class;
    }

    /**
     * @return mixed
     */
    public function getData($filter)
    {
        $grid = [];
        $period = CarbonPeriod::create($filter['from'], $filter['to']);
        foreach ($period as $dt) {
            $date = $dt->format('m/Y');
            $grid[$date] = 0;
        }

        $query = $this->model->select('month', 'value')
            ->whereDate('month','>=',$filter['from'])
            ->whereDate('month','<=',$filter['to'])
            ->orderBy('month','asc')->get()->each(function ($obj) use (&$grid) {
            $date = Carbon::createFromFormat('Y-m-d', $obj->month)->format('m/Y');
            $grid[$date] = $obj->value;
        });
        return $grid;
    }

}