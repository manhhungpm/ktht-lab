<?php

namespace App\Repositories\Brandname;

use App\Repositories\BaseRepository;
use App\Models\BrandnameOutlier;

class BrandnameOutlierDetailRepository extends BaseRepository
{
    public function model()
    {
        return BrandnameOutlier::class;
    }

    public function getList($keyword = null, $counting = false, $limit = 10, $offset = 0, $orderBy = 'date', $orderType = 'desc')
    {
        $query = $this->model->where('active', ACTIVE);

        if (!$counting) {
            $query->select('id', 'date', 'vip_outlier', 'pot_outlier', 'nor_outlier', 'vip_month_outlier', 'pot_month_outlier', 'nor_month_outlier', 'file_path');

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