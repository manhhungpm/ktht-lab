<?php

namespace App\Repositories\Brandname;

use App\Models\BrandnameAlias;
use App\Repositories\BaseRepository;

class BrandnameAliasRepository extends BaseRepository
{

    public function model()
    {
        return BrandnameAlias::class;
    }

    public function getList($keyword = null, $search = [], $counting = false, $limit = 10, $offset = 0, $orderBy = 'report_date', $orderType = 'desc')
    {
        $query = $this->model->where('active', ACTIVE);
        collect($search)->each(function ($item, $key) use ($query) {
            switch ($key) {
                case 'from':
                    $query->whereDate('report_date', '>=', $item);
                    break;
                case 'to':
                    $query->whereDate('report_date', '<=', $item);
            }
        });
        if (!$counting) {
            $query->select('report_date'

                , 'type', 'alias', '_1', '_2', '_3', '_4', '_5'
                , '_6', '_7', '_8', '_9', '_10', '_11_20', '_21_30', '_31_40', '_41_50', '_bigger50', 'msg_amount', 'cus_amount');
            if ($limit > 0) {
                $query->skip($offset)
                    ->take($limit);
            }

            if ($orderBy != null && $orderType != null) {
                $query->orderBy($orderBy, $orderType);
            }
            $query->orderBy('alias', 'asc');
        } else {

            return $query->count();
        }


        return $query->get();
    }
}