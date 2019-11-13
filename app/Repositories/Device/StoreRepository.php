<?php
/**
 * Created by PhpStorm.
 * User: manhhungpm
 * Date: 04/11/2019
 * Time: 22:04
 */
namespace App\Repositories\Device;

use App\Models\Store;
use App\Repositories\BaseRepository;

class StoreRepository extends BaseRepository
{
    public function model()
    {
        return Store::class;
    }

    public function getList($keyword = null, $counting = false, $limit = 10, $offset = 0, $orderBy = 'name', $orderType = 'asc')
    {
        $query = $this->model
            ->where('name', 'LIKE', "%$keyword%");

        if (!$counting) {
            $query->select('id', 'name', 'status', 'description', 'updated_at', 'created_at');
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

    public function addStore($arr)
    {
        $query = $this->model;
        $arr['status'] = '1';
        $query->fill($arr);
        return $query->save();
    }

    public function editStore($arr)
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
        $query = Store::where('name', 'LIKE', "%$keyword%")->where('status', ACTIVE);
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