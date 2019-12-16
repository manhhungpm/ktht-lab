<?php

namespace App\Repositories\Admin;

use App\Models\ActionsLog;
use App\Repositories\BaseRepository;

class LogRepository extends BaseRepository
{
    public function model()
    {
        return ActionsLog::class;
    }

    public function getList($keyword = null, $counting = false, $limit = 10, $offset = 0, $orderBy = 'username', $orderType = 'asc')
    {
        $query = $this->model
            ->where('username', 'LIKE', "%$keyword%");

        if (!$counting) {
            $query->with('users')->select('user_id', 'username', 'action_name', 'created_at',
                'class_name', 'old_value', 'new_value', 'object_name', 'object_id','ip');
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