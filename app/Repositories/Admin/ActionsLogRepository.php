<?php

namespace App\Repositories\Admin;

use App\Models\ActionsLog;
use App\Repositories\BaseRepository;

class ActionsLogRepository extends BaseRepository
{
    public function model()
    {
        return ActionsLog::class;
    }

    public function getList($keyword = null, $counting = false, $limit = 10, $offset = 0, $orderBy = 'occurred_at', $orderType = 'desc')
    {
        $query = $this->model->select('actions_logs.id', 'action_name', 'old_value', 'new_value', 'object_name',
            'object_id', 'actions_logs.created_at', 'user_id', 'users.name as name', 'ip')
            ->leftJoin('users', 'user_id', '=', 'users.id')
            ->where('name', 'LIKE', "%$keyword%")
            ->orWhere('action_name', 'LIKE', "%$keyword%");;
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

        return $query->get();
    }

    public function store($arr)
    {
        $actionsLog = new $this->model;
        $actionsLog->fill($arr);

        return $actionsLog->save();
    }
}
