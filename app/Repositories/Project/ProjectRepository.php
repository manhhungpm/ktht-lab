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

    public function getList($keyword = null, $search = [], $counting = false, $limit = 10, $offset = 0, $orderBy = 'name', $orderType = 'asc')
    {
        $query = $this->model->select('id', 'name', 'description', 'status', 'created_at', 'updated_at')
            ->where('name', 'LIKE', "%$keyword%");

//        dd($search);
        collect($search)->each(function ($item, $key) use ($query) {
            switch ($key) {
                case 'user':
                    if (isset($item)){
                        $query = $query->whereHas('user', function ($q) use ($item) {
                            $q->whereIn('user_id', $item);
                        });
                    }
                    break;
                case 'device_type':
                    if (isset($item)){
                        $query = $query->whereHas('deviceType', function ($q) use ($item) {
                            $q->whereIn('device_id', $item);
                        });
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
            $query->with('user')->with('deviceType');
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

    public function addProject($arr, $ip)
    {
//        dd($arr);
        $query = $this->model;
        $arr['status'] = '1';
        $query->fill($arr);
        if ($query->save()) {
            if ($arr['user_id'] && $arr['device_type_id']) {
                $query->user()->attach($arr['user_id']);
                $query->deviceType()->attach($arr['device_type_id']);
                fireEventActionLog(ADD, $query->getTable(), $query->id, $query->name, null, json_encode($query), $ip);
            }
            return true;
        }
        return false;
    }

    public function editProject($arr, $ip)
    {
        $query = $this->model->find($arr['id']);
        $oldUser = json_encode($query);
        if ($query != null) {
            $query->fill($arr);
            if ($query->save()) {
                $query->user()->detach();
                $query->deviceType()->detach();
                if ($arr['user_id'] && $arr['device_type_id']) {
                    $query->user()->attach($arr['user_id']);
                    $query->deviceType()->attach($arr['device_type_id']);
                    fireEventActionLog(UPDATE, $query->getTable(), $query->id, $query->name, $oldUser, json_encode($query), $ip);
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