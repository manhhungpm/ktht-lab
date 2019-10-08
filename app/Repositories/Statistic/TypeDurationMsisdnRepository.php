<?php

namespace App\Repositories\Statistic;

use App\Models\Statistic\TypeDurationMsisdn;
use App\Repositories\BaseRepository;

class TypeDurationMsisdnRepository extends BaseRepository
{
    public function model()
    {
        return TypeDurationMsisdn::class;
    }

    public function getData($filter)
    {
        $query = $this->model;

        $date = $filter['time_filter'];

        if (isset($date)) {
            $query = $query->select('month', 'value', 'msisdn_type_id')
                ->whereDate('month', '>=', $date[0])
                ->whereDate('month', '<=', $date[1]);
        }

        return $query->get();
    }
}