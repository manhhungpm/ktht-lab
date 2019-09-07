<?php

namespace App\Repositories\Brandname;

use App\Models\BrandnameDuplicateSegmentMonth;
use App\Repositories\BaseRepository;

class BrandnameDuplicateMonthSegmentRepository extends BaseRepository
{
    public function model()
    {
        return BrandnameDuplicateSegmentMonth::class;
    }

    public function getList($keyword = null, $search = [], $counting = false, $limit = 10, $offset = 0, $orderBy = 'date', $orderType = 'desc')
    {
        $query = $this->model->where('active',ACTIVE);

        collect($search)->each(function ($item, $key) use ($query) {
            switch ($key) {
                case 'from':
                    $query->whereDate('date', '>=', $item);
                    break;
                case 'to':
                    $query->whereDate('date', '<=', $item);
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