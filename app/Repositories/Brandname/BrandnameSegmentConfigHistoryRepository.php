<?php

namespace App\Repositories\Brandname;


use App\Models\BrandnameSmsSegmentConfigHistory;
use App\Repositories\BaseRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BrandnameSegmentConfigHistoryRepository extends BaseRepository
{
    public function model()
    {
        return BrandnameSmsSegmentConfigHistory::class;
    }

    public function getList($keyword = null, $search = [], $counting = false, $limit = 10, $offset = 0, $orderBy = 'when_updated', $orderType = 'desc')
    {
        $query = $this->model
            ->select( 'name', 'per_day', 'per_month', 'type_id', 'apply_from', 'apply_to', 'who_created', 'who_updated', 'when_created', 'when_updated', 'ip', 'status', 'active')
            ->where('config_id',$search)
            ;

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