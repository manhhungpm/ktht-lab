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

    public function getList($keyword = null, $counting = false, $limit = 10, $offset = 0, $orderBy = 'name', $orderType = 'asc')
    {
        $query = $this->model
            ->where('name', 'LIKE', "%$keyword%");

        if (!$counting) {
            $query->with('faculty')->select('id', 'name', 'status', 'description', 'updated_at', 'created_at','faculty_id');
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

    public function addClass($arr)
    {
        $query = $this->model;
        $arr['status'] = '1';
        $query->fill($arr);
        return $query->save();
    }

    public function editClass($arr)
    {
        $query = $this->model->find($arr['id']);
        if ($query != null) {
            $query->fill($arr);
            if ($query->save()) {
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