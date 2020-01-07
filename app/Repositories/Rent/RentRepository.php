<?php

namespace App\Repositories\Rent;

use App\Models\Rent;
use App\Repositories\BaseRepository;

class RentRepository extends BaseRepository
{
    public function model()
    {
        return Rent::class;
    }

    public function getList($keyword = null, $search = [], $counting = false, $limit = 10, $offset = 0, $orderBy = 'id', $orderType = 'asc')
    {
        $query = $this->model
            ->select('id', 'user_id', 'description', 'status', 'due_date', 'updated_at', 'created_at', 'start_date','priority',
                'leader_id','project_id')
            ->where('description', 'LIKE', "%$keyword%");

        collect($search)->each(function ($item, $key) use ($query) {
            switch ($key) {
                case 'start_date':
                    if(isset($item)) {
                        $query->whereDate('start_date','>=',$item[0])->whereDate('start_date','<=',$item[1]);
                    }
                    break;
                case 'due_date':
                    if(isset($item)) {
                        $query->whereDate('due_date','>=',$item[0])->whereDate('due_date','<=',$item[1]);
                    }
                    break;
                case 'device_type':
                    if(isset($item)) {
                        $query = $query->whereHas('deviceType', function ($q) use ($item) {
                            $q->whereIn('device_type_id', $item);
                        });
                    }
                    break;
                case 'status':
                    if (isset($item)) {
                        $query->whereIn('status', $item);
                    }
                    break;
                case 'project':
                    if (isset($item)) {
                        $query->whereIn('project_id', $item);
                    }
                    break;
                case 'leader':
                    if (isset($item)) {
                        $query->whereIn('leader_id', $item);
                    }
                    break;
                default:
                    break;
            }
        });

        if (!$counting) {
            $query->with('user')->with('deviceType')->with('project')->with('leader');
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

    public function addRent($arr, $ip)
    {
//        dd($arr);
        $query = $this->model;
        $arr['status'] = WAIT_APPROVED;
        $query->priority = $arr['priority']['id'];
        $query->user_id = $arr['user']['id'];
        $query->start_date = $arr['date_range'][0];
        $query->due_date = $arr['date_range'][1];
        $query->fill($arr);
        if ($query->save()) {
            if ($arr['device_type_id']) {

//                $query->deviceType()->attach($arr['device_type_id']);

                foreach ($arr['device_type_id'] as $key => $id) {
                    $query->deviceType()->attach($id, array('amount' => $arr['amount'][$key]));
                }

                fireEventActionLog(ADD, $query->getTable(), $query->id, $arr['user']['name'], null, json_encode($query), $ip);
            }
            return true;
        }
        return false;
    }

    public function editRent($arr, $ip)
    {
        $query = $this->model->find($arr['id']);
        $oldUser = json_encode($query);
        if ($query != null) {
            $query->priority = $arr['priority']['id'];
            $query->user_id = $arr['user']['id'];
            $query->start_date = $arr['date_range'][0];
            $query->due_date = $arr['date_range'][1];
            $query->fill($arr);
            if ($query->save()) {
                $query->deviceType()->detach();
                if ($arr['device_type_id']) {

//                    $query->deviceType()->attach($arr['device_type_id']);
                    foreach ($arr['device_type_id'] as $key => $id) {
                        $query->deviceType()->attach($id, array('amount' => $arr['amount'][$key]));
                    }

                    fireEventActionLog(UPDATE, $query->getTable(), $query->id, $arr['user']['name'], $oldUser, json_encode($query), $ip);
                }
                return true;
            }
            return false;
        }
        return false;
    }

    public function setPay($id)
    {
        $query = $this->model->where('id', $id);
        if ($query) {
            $query->update(['status' => PAY]);
            return $query;
        }
    }

    public function setBorrow($id)
    {
        $query = $this->model->where('id', $id);
        if ($query) {
            $query->update(['status' => BORROW]);
            return $query;
        }
    }

    public function setApproved($id)
    {
        $query = $this->model->where('id', $id);
        if ($query) {
            $query->update(['status' => APPROVED]);
            return $query;
        }
    }

    public function setDeny($id)
    {
        $query = $this->model->where('id', $id);
        if ($query) {
            $query->update(['status' => DENY]);
            return $query;
        }
    }
}
