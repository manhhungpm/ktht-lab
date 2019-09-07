<?php

namespace App\Repositories\Brandname;

use App\Models\BrandnameSegmentMonth;
use App\Repositories\BaseRepository;


class ReportMonthSegmentRepository extends BaseRepository{
    public function model()
    {
        return BrandnameSegmentMonth::class;
    }

    public function getList($keyword = null, $search = [], $counting = false, $limit = 10, $offset = 0, $orderBy = 'date', $orderType = 'desc')
    {
        $query = $this->model->where('active',ACTIVE);

        collect($search)->each(function ($item, $key) use ($query) {
            switch ($key) {
                case 'time':
                    $query->whereDate('date', '>=', $item[0])->whereDate('date', '<=', $item[1]);
                    break;
            }
        });

        if (!$counting) {
            $query->select('date','type','amount','vip','potential','normal','total');
            if ($limit > 0) {
                $query->skip($offset)
                    ->take($limit);
            }

            if ($orderBy != null && $orderType != null) {
                $query->orderBy($orderBy, $orderType);
            }
        } else {

            return $query->count();
        }


        return $query->get();
    }
}