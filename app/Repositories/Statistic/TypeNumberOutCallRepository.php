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

        if (isset($filter)) {
            $query = $query->select('month', 'value','msisdn_type_id')->whereDate('month', $filter)->orderBy('msisdn_type_id', 'asc');
        }

        return $query->get();
    }
}