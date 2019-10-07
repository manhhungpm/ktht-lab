<?php

namespace App\Repositories\Statistic;

use App\Models\Statistic\TypeDurationType;
use App\Repositories\BaseRepository;

class TypeDurationTypeRepository extends BaseRepository
{
    public function model()
    {
        return TypeDurationType::class;
    }

    public function getData($filter)
    {
        $query = $this->model;

        if (isset($filter)) {
            $query = $query->select('month', 'value')->whereDate('month', $filter)->orderBy('msisdn_type_id', 'asc')->orderBy('call_duration_type_id','asc');
        }

        return $query->get();
    }
}