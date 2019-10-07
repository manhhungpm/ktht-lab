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

        if (isset($filter)) {
            $query = $query->select('month', 'value_one_way','value_two_way')->whereDate('month', $filter)->orderBy('msisdn_type_id', 'asc');
        }

        return $query->get();
    }
}