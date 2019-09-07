<?php

namespace App\Repositories\Brandname;

use App\Repositories\BaseRepository;
use App\Models\BrandnameWrongTime;

class BrandnameWrongTimeRepository extends BaseRepository
{
    public function model()
    {
        return BrandnameWrongTime::class;
    }

    public function getList($keyword = null, $counting = false, $limit = 10, $offset = 0, $orderBy = 'date', $orderType = 'desc')
    {
        $query = $this->model->select('id', 'date', 'active', 'file_path');

        if (!$counting) {
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