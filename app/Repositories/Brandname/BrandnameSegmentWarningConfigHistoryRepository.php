<?php

namespace App\Repositories\Brandname;

use App\Models\BrandnameSegmentWarningConfigHistory;
use App\Models\BrandnameSmsType;
use App\Repositories\BaseRepository;

class BrandnameSegmentWarningConfigHistoryRepository extends BaseRepository
{
    public function model()
    {
        return BrandnameSegmentWarningConfigHistory::class;
    }

    public function getList($keyword = null, $search = [], $counting = false, $limit = 10, $offset = 0, $orderBy = 'when_updated', $orderType = 'desc')
    {
        $query = $this->model
            ->select('name', 'high_warning_from', 'high_warning_to', 'danger_warning_from', 'danger_warning_to', 'crisis_warning_from', 'crisis_warning_to', 'type_id', 'apply_from'
                , 'apply_to', 'who_created', 'who_updated', 'when_created', 'when_updated', 'ip', 'active', 'high_warning_from_m', 'high_warning_to_m', 'danger_warning_from_m',
                'danger_warning_to_m', 'crisis_warning_from_m', 'crisis_warning_to_m')
            ->where('config_id', $search);

        if (!$counting) {
            $query->with('brandnameSmsType:id,name')
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