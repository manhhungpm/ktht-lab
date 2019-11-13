<?php
/**
 * Created by PhpStorm.
 * User: manhhungpm
 * Date: 09/11/2019
 * Time: 23:16
 */

namespace App\Repositories\Project;

use App\Models\Project;
use App\Repositories\BaseRepository;

class ProjectRepository extends BaseRepository
{
    public function model()
    {
        return Project::class;
    }

    public function getList($keyword = null, $counting = false, $limit = 10, $offset = 0, $orderBy = 'name', $orderType = 'asc')
    {
        $query = $this->model
            ->where('name', 'LIKE', "%$keyword%");

        if (!$counting) {
            $query->select('id', 'name', 'description', 'status', 'created_at', 'updated_at')->with('user')->with('deviceType');
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

    public function addProject($arr)
    {
//        dd($arr);
        $query = $this->model;
        $arr['status'] = '1';
        $query->fill($arr);
        if ($query->save()) {
            if ($arr['user_id'] && $arr['device_type_id']) {
                $query->user()->attach($arr['user_id']);
                $query->deviceType()->attach($arr['device_type_id']);
            }
            return true;
        }
        return false;
    }

    public function editProject($arr)
    {
        $query = $this->model->find($arr['id']);
        if ($query != null) {
            $query->fill($arr);
            if ($query->save()) {
                $query->user()->detach();
                $query->deviceType()->detach();
                if ($arr['user_id'] && $arr['device_type_id']) {
                    $query->user()->attach($arr['user_id']);
                    $query->deviceType()->attach($arr['device_type_id']);
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

    public function listingAll($isCounting = false, $keyword = null, $limit = 10, $offset = 0)
    {
        $query = Project::where('name', 'LIKE', "%$keyword%")->where('status', ACTIVE);
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