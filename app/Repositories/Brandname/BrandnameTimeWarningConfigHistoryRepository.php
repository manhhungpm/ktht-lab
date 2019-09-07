<?php

namespace App\Repositories\Brandname;


use App\Models\BrandnameTimeWarningConfigHistory;
use App\Repositories\BaseRepository;


class BrandnameTimeWarningConfigHistoryRepository extends BaseRepository
{
    public function model()
    {
        return BrandnameTimeWarningConfigHistory::class;
    }

    public function getList($keyword = null, $search = [], $counting = false, $limit = 10, $offset = 0, $orderBy = 'when_updated', $orderType = 'desc')
    {
        $query = $this->model
            ->select('type_id', 'apply_from', 'apply_to', 'week_day','high_warning_from','high_warning_from',
                'high_warning_to', 'danger_warning_from','danger_warning_to','crisis_warning_from','crisis_warning_to',
                'time_frame', 'who_updated', 'when_updated', 'ip', 'active')
            ->where('config_id',$search)
        ;

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