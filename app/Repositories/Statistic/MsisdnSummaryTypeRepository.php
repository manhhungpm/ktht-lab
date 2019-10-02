<?php

namespace App\Repositories\Statistic;

use App\Models\Statistic\MsisdnSummaryType;
use App\Repositories\BaseRepository;


class MsisdnSummaryTypeRepository extends BaseRepository
{
    public function model()
    {
        return MsisdnSummaryType::class;
    }

    public function getList($keyword = null, $search = [], $counting = false, $limit = 10, $offset = 0, $orderBy = 'msisdn', $orderType = 'desc')
    {
        $query = $this->model
            ->where(function ($query) use ($keyword) {
                $query->where('msisdn', 'LIKE', "%$keyword%");
            });

        collect($search)->each(function ($item, $key) use ($query) {
            if (isset($item)) {
                $query->where('duration_type_id', $item);
            }
        });

        if (!$counting) {
            $query->with('durationType')->select('id', 'msisdn', 'num_call_out', 'sum_duration_call_out', 'num_call_label_spam', 'num_call_label_not_spam', 'num_call_not_label', 'duration_type_id');
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