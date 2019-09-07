<?php
/**
 * Created by PhpStorm.
 * User: hungnm
 * Date: 7/9/2019
 * Time: 4:47 PM
 */

namespace App\Repositories\Admin;

use App\Models\Api;
use App\Repositories\BaseRepository;

class ApiRepository extends BaseRepository
{
    public function model()
    {
        return Api::class;
    }

    public function getList($keyword = null, $counting = false, $limit = 10, $offset = 0, $orderBy = 'when_updated', $orderType = 'desc')
    {
        $query = $this->model->where(function ($q) use ($keyword) {
            $q->where('name', 'LIKE', "%$keyword%")
                ->orWhere('display_name', 'LIKE', "%$keyword%");
        });

        if (!$counting) {
            $query->select('id', 'name', 'display_name', 'description', 'active', 'who_updated', 'when_updated'); // ko count ms select - count roi thi ko can select nua
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

    public function setDisable($id)
    {
        $query = $this->model->where('id', $id)->update(['active' => 0]);

        if ($query) {
            return true;
        } else {
            return false;
        }
    }

    public function setActive($id)
    {
        $query = $this->model->where('id', $id)->update(['active' => 1]);

        if ($query) {
            return true;
        } else {
            return false;
        }
    }

    public function addApi($arr)
    {
        $query = $this->model;
        $arr['active'] = '1';
        $arr['version'] = '1';
        $arr['who_created'] = \auth()->user()->name;
        $query->fill($arr);
        return $query->save();
    }

    public function editApi($arr)
    {
        $arr['who_updated'] = \auth()->user()->name;
        $query = $this->model->find($arr['id']);
        if ($query != null) {
            $query->fill($arr);
            return $query->save();
        }
        return false;
    }

}