<?php

namespace App\Repositories\Spam;

use App\Repositories\BaseRepository;

use App\Models\SpamReviewWrong;
use Carbon\Carbon;

class SpamReviewWrongRepository extends BaseRepository
{
    /**
     * @return string
     */
    public function model()
    {
        return SpamReviewWrong::class;
    }

    /**
     * @return mixed
     */
    public function dashboardCount()
    {
        $date = Carbon::yesterday()->format('Y-m-d');
        return $this->model
            ->where(function ($query) {
                return $query->where('label_name', $this->model::UNLABEL)
                    ->orWhereNull('label_name');
            })
            ->where('data_time', $date)
            ->count();
    }
}