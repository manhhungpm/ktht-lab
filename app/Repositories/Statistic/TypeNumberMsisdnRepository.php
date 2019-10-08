<?php

namespace App\Repositories\Statistic;

use App\Models\Statistic\TypeNumberMsisdn;
use App\Repositories\BaseRepository;

class TypeNumberMsisdnRepository extends BaseRepository
{
    public function model()
    {
        return TypeNumberMsisdn::class;
    }

    public function getData($filter)
    {
        $query = $this->model;

        $date = $filter['time_filter'];

        if (isset($date)) {
            $query = $query->select('month', 'value','msisdn_type_id')
                ->whereDate('month','>=', $date[0])
                ->whereDate('month','<=',$date[1]);
        }

        return $query->get();
    }
}