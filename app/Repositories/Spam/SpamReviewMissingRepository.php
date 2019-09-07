<?php

namespace App\Repositories\Spam;

use App\Repositories\BaseRepository;
use App\Models\SpamReviewMissing;
use Carbon\Carbon;

class SpamReviewMissingRepository extends BaseRepository
{
    /**
     * @return string
     */
    public function model()
    {
        return SpamReviewMissing::class;
    }

    public function dashboardCount(){
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