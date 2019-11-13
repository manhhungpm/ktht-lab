<?php

namespace App\Repositories\Statistic;

use App\Models\Statistic\TypePercentCall;
use App\Repositories\BaseRepository;

class TypePercentCallRepository extends BaseRepository
{
    public function model()
    {
        return TypePercentCall::class;
    }

    public function getData($filter)
    {
        $query = $this->model;

        $date = $filter['time_filter'];

        if (isset($date)) {
            $query = $query->select('month', 'msisdn_type_id', 'value_one_way', 'value_two_way')
                ->whereDate('month','>=', $date[0])
                ->whereDate('month','<=', $date[1]);
        }

        return $query->get();
    }
}