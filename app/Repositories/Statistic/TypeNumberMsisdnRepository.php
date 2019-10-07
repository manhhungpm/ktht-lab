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

        if (isset($filter)) {
            $query = $query->select('month', 'value')->whereDate('month', $filter)->orderBy('msisdn_type_id', 'asc');
        }

        return $query->get();
    }
}