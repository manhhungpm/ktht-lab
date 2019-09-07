<?php

namespace App\Repositories\Brandname;

use App\Models\BrandnameAliasMonth;
use App\Repositories\BaseRepository;

class ReportMonthAliasRepository extends BaseRepository
{

    public function model()
    {
        return BrandnameAliasMonth::class;
    }

    public function getList($keyword = null, $search = [], $counting = false, $limit = 10, $offset = 0, $orderBy = 'date', $orderType = 'desc')
    {
        $query = $this->model->where('active', ACTIVE);
        collect($search)->each(function ($item, $key) use ($query) {
            switch ($key) {
                case 'time':
                    $query->whereDate('date', '>=', $item[0])->whereDate('date', '<=', $item[1]);
                    break;
            }
        });
        if (!$counting) {
            $query->select('date'
                , 'type', 'alias', '_1', '_2', '_3', '_4', '_5'
                , '_6', '_7', '_8', '_9', '_10', '_11_15', '_16_20', '_21_25', '_26_30',
                '_31_35','_36_40','_41_45','_46_50','_51_55','_56_60','_61_80','_81_100','_101_150', '_bigger150', 'msg_amount', 'cus_amount');
            if ($limit > 0) {
                $query->skip($offset)
                    ->take($limit);
            }

            if ($orderBy != null && $orderType != null) {
                $query->orderBy($orderBy, $orderType);
            }
            $query->orderBy('alias','asc');
        } else {

            return $query->count();
        }


        return $query->get();
    }
}