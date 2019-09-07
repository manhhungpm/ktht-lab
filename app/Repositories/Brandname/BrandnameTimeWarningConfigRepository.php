<?php

namespace App\Repositories\Brandname;


use App\Models\BrandnameTimeWarningConfig;
use App\Models\BrandnameSmsType;
use App\Repositories\BaseRepository;


class BrandnameTimeWarningConfigRepository extends BaseRepository
{
    public function model()
    {
        return BrandnameTimeWarningConfig::class;
    }

    public function getList($keyword = null, $search = [], $counting = false, $limit = 10, $offset = 0, $orderBy = 'when_updated', $orderType = 'desc')
    {
        $query = $this->model
            ->where(function ($query) use ($keyword) {
//                $query->where('name', 'LIKE', "%$keyword%");
            });

        if (!$counting) {
            $query->select('id', 'type_id', 'apply_from', 'apply_to', 'week_day', 'time_frame', 'who_created', 'who_updated', 'when_created', 'when_updated', 'ip', 'active',
                'high_warning_from','high_warning_to','danger_warning_from'
                ,'danger_warning_to','crisis_warning_from','crisis_warning_to');
            $query->with('brandnameSmsType:id,name,group_id');
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
//        dd($arr);
        $query = $this->model;
        $query->who_created = auth()->user()->name;
        $query->who_updated = auth()->user()->name;
        $query->ip = $ip;
        $query->active = 0;

        $day = array(
            "day" => $arr['week_day']
        );
        $week_day = json_encode($day); //"{"day":["1","2","3"]}"

        $time = array(
            "time_frame" => $arr['time_frame']
        );
        $time_frame = json_encode($time); //"{"time_frame":[{"from":"11","to":"12"},{"from":"123","to":"131"}]}"

        $arr['time_frame'] = $time_frame;
        $arr['week_day'] = $week_day;
        $query->fill($arr);
        return $query->save();
    }

    public function edit($arr, $ip)
    {
        $query = $this->model->find($arr['id']);
        if ($query) {
            $query->who_updated = auth()->user()->name;
            $query->ip = $ip;

            $day = array(
                "day" => $arr['week_day']
            );
            $week_day = json_encode($day);

            $time = array(
                "time_frame" => $arr['time_frame']
            );
            $time_frame = json_encode($time);

            $arr['time_frame'] = $time_frame;
            $arr['week_day'] = $week_day;

            $query->fill($arr);

            return $query->save();
        }

        return false;
    }

    public function active($id,$sms_type_id, $ip)
    {
        $isExits = BrandnameSmsType::where('id',$sms_type_id)->where('active',ACTIVE)->exists();

        if($isExits == false){
            return CODE_ERROR_ACTIVE_CONFIG_WHEN_SMS_TYPE_DISABLE;
        } else {
            $config = $this->model->find($id);

            if ($config) {
                $config->active = 1;
                $config->ip = $ip;
                return $config->save();
            }
        }
    }

    public function inactive($id, $ip)
    {
        $config = $this->model->find($id);

        if ($config) {
            $config->active = 0;
            $config->ip = $ip;
            return $config->save();
        }
    }
}