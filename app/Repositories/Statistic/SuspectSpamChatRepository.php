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
        $query = $this->model;
        $dateRange = $filter['time_filter'];
        $grid = [];
        $period = CarbonPeriod::create($dateRange[0], $dateRange[1]);

        foreach ($period as $dt) {
            $date = $dt->format('d/m/Y');
            $grid[$date] = 0;
        }

        $query =$query->select('month', 'value')
            ->whereDate('month', '>=', $dateRange[0])
            ->whereDate('month', '<=', $dateRange[1])
            ->get()
            ->each(function ($obj) use (&$grid) {
                $date = Carbon::createFromFormat('Y-m-d', $obj->month)->format('d/m/Y');
                $grid[$date] = $obj->value;
            })
        ;

        return $grid;
    }

}