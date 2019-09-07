<?php

namespace App\Repositories\Spam;

use App\Models\SpamLabel;
use App\Repositories\BaseRepository;

use App\Models\SpamPattern;

class SpamPatternRepository extends BaseRepository
{
    /**
     * @return string
     */
    public function model()
    {
        return SpamPattern::class;
    }

    /**
     * @return mixed
     */
    public function dashboardCount()
    {
        return $this->model
            ->where('label_name', $this->model::UNLABEL)
            ->orWhereNull('label_name')
            ->count();
    }


    public function spamPatternCountBetween($from, $to, $label = null, $application = null){
        $query = $this->model->whereBetween('when_updated', [$from, $to]);
        if($label){
            $labels = SpamLabel::where('name',$label)->orWhere('parent',$label)->pluck('name');
            $query->whereIn('label_name',$labels);
        }
        if ($application) {
            $query->where('application_name', $application);
        }
        return $query->count();
    }
}