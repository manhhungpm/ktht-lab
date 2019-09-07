<?php

namespace App\Repositories;

use App\Models\A2pPattern;

class A2pPatternRepository extends BaseRepository
{
    /**
     * @return string
     */
    public function model()
    {
        return A2pPattern::class;
    }

    /**
     * @return mixed
     */
    public function dashboardRemainA2PCount()
    {
        return $this->model
            ->where('label_name', $this->model::UNLABEL)
            ->orWhereNull('label_name')
            ->count();
    }

    public function dashboardA2PCount()
    {
        return $this->model
            ->where('label_name', '<>', $this->model::UNLABEL)
            ->orWhereNull('label_name')
            ->count();
    }
}