<?php

namespace App\Repositories;

use App\Models\A2pKeyword;

class A2pKeywordRepository extends BaseRepository
{
    /**
     * @return string
     */
    public function model()
    {
        return A2pKeyword::class;
    }

    /**
     * @return mixed
     */
    public function dashboardCount()
    {
        return $this->model
            ->where('active', $this->model::ACTIVE)
            ->count();
    }

}