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

        if (isset($filter)) {
            $query = $query->select('month', 'value')->whereDate('month', $filter)->orderBy('msisdn_type_id', 'asc');
        }

        return $query->get();
    }
}