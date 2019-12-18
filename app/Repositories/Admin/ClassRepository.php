<?php

namespace App\Repositories\Admin;

use App\Models\Classes;
use App\Repositories\BaseRepository;

class ClassRepository extends BaseRepository
{
    public function model()
    {
        return Classes::class;
    }

    public function getList($keyword = null, $search = [], $counting = false, $limit = 10, $offset = 0, $orderBy = 'name', $orderType = 'asc')
    {
        $query = $this->model->select('id', 'name', 'status', 'description', 'updated_at', 'created_at', 'faculty_id')
            ->where('name', 'LIKE', "%$keyword%");

        collect($search)->each(function ($item, $key) use ($query) {
            switch ($key) {
                case 'faculty':
                    if(isset($item)) {
                        $query->whereIn('faculty_id', $item);
                    }
                    break;
                case 'status':
                    if (isset($item)) {
                        $query->whereIn('status', $item);
                    }
                    break;
                default:
                    break;
            }
        });

        if (!$counting) {
            $query->with('faculty');
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

    public function addClass($arr, $ip)
    {
        $query = $this->model;
        $arr['status'] = '1';
        $query->fill($arr);
        fireEventActionLog(ADD, $query->getTable(), $query->id, $query->name, null, json_encode($query), $ip);
        return $query->save();
    }

    public function editClass($arr, $ip)
    {
        $query = $this->model->find($arr['id']);
        $oldUser = json_encode($query);
        if ($query != null) {
            $query->fill($arr);
            if ($query->save()) {
                fireEventActionLog(UPDATE, $query->getTable(), $query->id, $query->name, $oldUser, json_encode($query), $ip);
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

    public function listingAll($isCounting = false, $keyword = null, $limit = 10, $offset = 0)
    {
        $query = Classes::where('name', 'LIKE', "%$keyword%")->where('status', ACTIVE);
        if (!$isCounting) {
            $query->select('id', 'name')
                ->skip($offset)
                ->take($limit)
                ->orderBy('name', 'asc');
        } else {
            return $query->count();
        }

        return $query->get();
    }
}