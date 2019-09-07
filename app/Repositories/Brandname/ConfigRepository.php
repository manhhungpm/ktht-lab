<?php

namespace App\Repositories\Brandname;

use App\Models\BrandnameThreshold;
use App\Repositories\BaseRepository;

class ConfigRepository extends BaseRepository
{
    const VBROADCAST = 'V-BROADCAST';
    const OTHERBROADCAST = 'OTHER-BROADCAST';

    public function model()
    {
        return BrandnameThreshold::class;
    }

    public function getList($keyword = null, $counting = false, $limit = 10, $offset = 0, $orderBy = 'date_threshold', $orderType = 'desc')
    {
        $query = $this->model->select('id', 'date_threshold', 'month_threshold', 'br_type', 'active', 'who_created', 'when_created','when_updated');;

        if (!$counting) {
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

    public function setActive($arr)
    {
        $query1 = $this->model->where('br_type', 'LIKE', $arr['br_type'])->where('active', 1)->update(['active' => 0]);
        $query = $this->model->where('id', $arr['id'])->update(['active' => 1]);

        if ($query) {
            if ($query1)
                return true;
        } else {
            return false;
        }
    }

    public function addConfig($arr)
    {
        $query = $this->model;
        $arr['who_created'] = \auth()->user()->name;
        $arr['active'] = '0';
        $arr['version'] = '1';
        $query->fill($arr);
        return $query->save();
    }

    public function editConfig($arr)
    {
        $query = $this->model->find($arr['id']);
        if ($query != null) {
            $arr['who_updated'] = \auth()->user()->name;
            $query->fill($arr);
            return $query->save();
        }
        return false;
    }

    public function getVBroadCast()
    {
        $query = $this->model
            ->select('date_threshold', 'month_threshold')
            ->where('br_type', 'LIKE', 'V-BROADCAST')
            ->where('active', ACTIVE);

        return $query->get();
    }

    public function getOtherBroadCast()
    {
        $query = $this->model
            ->select('date_threshold', 'month_threshold')
            ->where('br_type', 'LIKE', self::OTHERBROADCAST)
            ->where('active', ACTIVE);

        return $query->get();
    }
}