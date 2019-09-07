<?php

namespace App\Repositories\Brandname;

use App\Models\BrandnameSmsTimeFrameConfigHistory;
use App\Repositories\BaseRepository;

class BrandnameTimeFrameConfigHistoryRepository extends BaseRepository
{
    public function model()
    {
        return BrandnameSmsTimeFrameConfigHistory::class;
    }

    public function getList($keyword = null, $search = [], $counting = false, $limit = 10, $offset = 0, $orderBy = 'when_updated', $orderType = 'desc')
    {
        $query = $this->model
            ->select('type_id', 'apply_from', 'apply_to', 'week_day', 'time_frame', 'who_updated', 'when_updated', 'ip', 'active')
            ->where('config_id', $search);

        if (!$counting) {
            $query->with('brandnameSmsType:id,name,group_id')
                ->with('brandnameSmsType.brandnameSmsGroup:id,name');

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