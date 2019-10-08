<?php

namespace App\Repositories\Statistic;

use App\Models\Statistic\TypeNumberOutCall;
use App\Repositories\BaseRepository;

class TypeNumberOutCallRepository extends BaseRepository
{
    public function model()
    {
        return TypeNumberOutCall::class;
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