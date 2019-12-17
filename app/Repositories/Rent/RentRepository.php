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

    public function getList($keyword = null, $counting = false, $limit = 10, $offset = 0, $orderBy = 'id', $orderType = 'asc')
    {
        $query = $this->model
            ->where('description', 'LIKE', "%$keyword%");

        if (!$counting) {
            $query->select('id', 'user_id', 'description', 'status', 'due_date', 'updated_at', 'created_at', 'start_date')
                ->with('user')->with('deviceType');
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
//        dd($arr['amount']);

        $query = $this->model;
        $arr['status'] = '1';
        $query->user_id = $arr['user']['id'];
        $query->start_date = $arr['date_range'][0];
        $query->due_date = $arr['date_range'][1];
        $query->fill($arr);
        if ($query->save()) {
            if ($arr['device_type_id']) {

//                $query->deviceType()->attach($arr['device_type_id']);

                foreach ($arr['device_type_id'] as $key => $id){
                    $query->deviceType()->attach($id, array('amount'=>$arr['amount'][$key]));
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
            $query->user_id = $arr['user']['id'];
            $query->start_date = $arr['date_range'][0];
            $query->due_date = $arr['date_range'][1];
            $query->fill($arr);
            if ($query->save()) {
                $query->deviceType()->detach();
                if ($arr['device_type_id']) {

//                    $query->deviceType()->attach($arr['device_type_id']);
                    foreach ($arr['device_type_id'] as $key => $id){
                        $query->deviceType()->attach($id, array('amount'=>$arr['amount'][$key]));
                    }

                    fireEventActionLog(UPDATE, $query->getTable(), $query->id, $arr['user']['name'], $oldUser, json_encode($query), $ip);
                }
                return true;
            }
            return false;
        }
        return false;
    }

    public function setActive($id)
    {
        $query = $this->model->where('id', $id);
        if ($query) {
            $query->update(['status' => ACTIVE]);
            return $query;
        }
    }

    public function setDisable($id)
    {
        $query = $this->model->where('id', $id);
        if ($query) {
            $query->update(['status' => INACTIVE]);
            return $query;
        }
    }
}