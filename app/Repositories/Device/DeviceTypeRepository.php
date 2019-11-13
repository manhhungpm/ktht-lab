<?php
/**
 * Created by PhpStorm.
 * User: manhhungpm
 * Date: 05/11/2019
 * Time: 22:50
 */

namespace App\Repositories\Device;

use App\Models\DeviceType;
use App\Repositories\BaseRepository;

class DeviceTypeRepository extends BaseRepository
{
    public function model()
    {
        return DeviceType::class;
    }

    public function getList($keyword = null, $counting = false, $limit = 10, $offset = 0, $orderBy = 'name', $orderType = 'asc')
    {
        $query = $this->model
            ->where('name', 'LIKE', "%$keyword%");

        if (!$counting) {
            $query->select('id', 'name', 'display_name', 'amount', 'status', 'description', 'updated_at', 'created_at',
                'store_id', 'device_group_id')
                ->with('store')->with('deviceGroup');
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

    public function addDeviceType($arr)
    {
        $query = $this->model;
        $arr['status'] = '1';
        $query->fill($arr);
        return $query->save();
    }

    public function editDeviceType($arr)
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
        $query = DeviceType::where('name', 'LIKE', "%$keyword%")->where('status', ACTIVE);
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