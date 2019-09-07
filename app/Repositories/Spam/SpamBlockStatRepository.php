<?php

namespace App\Repositories\Spam;

use App\Repositories\BaseRepository;
use App\Models\GenericStatistic;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SpamBlockStatRepository extends BaseRepository
{
    /**
     * @return string
     */
    public function model()
    {
        return GenericStatistic::class;
    }

    public function getList($keyword = null, $search = [], $counting = false, $limit = 10, $offset = 0, $orderBy = 'data_time', $orderType = 'desc')
    {
        $query = $this->model->select('data_time', 'data')
            ->where('type', $this->model::TYPE_SPAM_BLOCK);


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

        $stats = $query->get()->map(function ($item, $key){
            $stat = json_decode($item->data);
            $stat->data_time = $item->data_time;
            return $stat;
        });

        return $stats;

    }


}