<?php

namespace App\Repositories\Statistic;

use App\Models\Statistic\SuspectSpamChart;
use App\Repositories\BaseRepository;
use Carbon\Carbon;


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
    public function listing($filter)
    {
        $query = $this->model->select('month','value')->get()->map(function($obj){
           $obj->month = Carbon::createFromFormat('Y-m-d',$obj)->format('m/Y');
           return $obj;
        });
        return $query;
    }

}