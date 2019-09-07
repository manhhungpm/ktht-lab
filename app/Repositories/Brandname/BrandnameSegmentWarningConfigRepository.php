<?php

namespace App\Repositories\Brandname;

use App\Models\BrandnameSegmentWarningConfig;
use App\Models\BrandnameSmsType;
use App\Repositories\BaseRepository;

class BrandnameSegmentWarningConfigRepository extends BaseRepository
{
    /**
     * @return string
     */

    public function model()
    {
        return BrandnameSegmentWarningConfig::class;
    }

    public function getList($keyword = null, $search = [], $counting = false, $limit = 10, $offset = 0, $orderBy = 'when_updated', $orderType = 'desc')
    {
        $query = $this->model
            ->where(function ($query) use ($keyword) {
//                $query->where('name', 'LIKE', "%$keyword%");
            });

        if (!$counting) {
            $query->select('id','name', 'high_warning_from', 'high_warning_to', 'danger_warning_from', 'danger_warning_to', 'crisis_warning_from', 'crisis_warning_to', 'type_id', 'apply_from'
                , 'apply_to', 'who_created', 'who_updated', 'when_created', 'when_updated', 'ip', 'active', 'high_warning_from_m', 'high_warning_to_m', 'danger_warning_from_m',
                'danger_warning_to_m', 'crisis_warning_from_m', 'crisis_warning_to_m', 'is_warning');
            $query->with('brandnameSmsType:id,name');
            $query->with('brandnameSmsType.brandnameSmsGroup:id,name');
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

    public function add($arr, $ip)
    {
        $brandnameSmsSegmentConfig = new $this->model();
        $brandnameSmsSegmentConfig->fill($arr);
        $brandnameSmsSegmentConfig->who_created = auth()->user()->name;
        $brandnameSmsSegmentConfig->who_updated = auth()->user()->name;
        $brandnameSmsSegmentConfig->ip = $ip;
        $brandnameSmsSegmentConfig->active = INACTIVE;

        return $brandnameSmsSegmentConfig->save();
    }

    public function edit($arr, $ip)
    {
        $brandnameSmsSegmentConfig = $this->model->find($arr['id']);
        if ($brandnameSmsSegmentConfig) {
            $brandnameSmsSegmentConfig->fill($arr);
            $brandnameSmsSegmentConfig->who_updated = auth()->user()->name;
            $brandnameSmsSegmentConfig->ip = $ip;

            return $brandnameSmsSegmentConfig->save();
        }
        return false;
    }

    public function listingSmsType($isCounting = false, $keyword = null, $limit = 10, $offset = 0)
    {
        $query = BrandnameSmsType::where('name', 'LIKE', "%$keyword%");

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

    public function active($id,$sms_type_id, $ip)
    {
        if(!BrandnameSmsType::where('id',$sms_type_id)->where('active',ACTIVE)->exists()){
            return CODE_ERROR_ACTIVE_CONFIG_WHEN_SMS_TYPE_DISABLE;
        }

        $brandnameSmsSegmentConfig = $this->model->find($id);

        if ($brandnameSmsSegmentConfig) {
            $brandnameSmsSegmentConfig->active = 1;
            $brandnameSmsSegmentConfig->ip = $ip;
            return $brandnameSmsSegmentConfig->save();
        }
    }

    public function inactive($id, $ip)
    {
        $brandnameSmsSegmentConfig = $this->model->find($id);

        if ($brandnameSmsSegmentConfig) {
            $brandnameSmsSegmentConfig->active = 0;
            $brandnameSmsSegmentConfig->ip = $ip;
            return $brandnameSmsSegmentConfig->save();
        }
    }

    public function changeStatus($arr, $ip)
    {
        $brandnameSmsSegmentConfig = $this->model->find($arr['id']);

        if ($brandnameSmsSegmentConfig) {
            $brandnameSmsSegmentConfig->fill($arr);
            $brandnameSmsSegmentConfig->ip = $ip;
            return $brandnameSmsSegmentConfig->save();
        }
    }


}