<?php
/**
 * Created by PhpStorm.
 * User: hungnm
 * Date: 6/29/2019
 * Time: 2:52 PM
 */

namespace App\Repositories\Admin;

use App\Models\Role;
use App\Repositories\BaseRepository;

class RoleRepository extends BaseRepository
{
    public function model()
    {
        return Role::class;
    }

    public function getList($keyword = null, $counting = false, $limit = 10, $offset = 0, $orderBy = 'name', $orderType = 'asc')
    {
        $query = new Role();
        $query = $query
            ->where('name', 'LIKE', "%$keyword%");

        if (!$counting) {
            $query->select('id', 'name', 'active', 'description');
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

    public function addRole($arr)
    {
        $query = $this->model;
        $arr['active'] = '1';
        $query->fill($arr);
        return $query->save();
    }

    public function editRole($arr)
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
            $query->update(['active' => ACTIVE]);
            return $query;
        }
    }

    public function setDisable($id)
    {
        $query = $this->model->where('id', $id);
        if ($query) {
            $query->update(['active' => INACTIVE]);
            return $query;
        }
    }

    public function listingAll($isCounting = false, $keyword = null, $limit = 10, $offset = 0)
    {
        $query = Role::where('name', 'LIKE', "%$keyword%")->where('active', ACTIVE);
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