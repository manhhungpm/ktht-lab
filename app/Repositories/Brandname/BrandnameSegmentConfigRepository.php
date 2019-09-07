<?php

namespace App\Repositories\Brandname;

use App\Models\BrandnameSegment;
use App\Models\BrandnameSmsSegmentConfig;
use App\Models\BrandnameSmsType;
use App\Models\SpamApplication;
use App\Models\SpamLabel;
use App\Repositories\BaseRepository;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BrandnameSegmentConfigRepository extends BaseRepository
{
    /**
     * @return string
     */

    public function model()
    {
        return BrandnameSmsSegmentConfig::class;
    }

    public function getList($keyword = null, $search = [], $counting = false, $limit = 10, $offset = 0, $orderBy = 'when_updated', $orderType = 'desc')
    {
        $query = $this->model
            ->where(function ($query) use ($keyword) {
                $query->where('name', 'LIKE', "%$keyword%");
            });

        if (!$counting) {
            $query->select('id', 'name', 'per_day', 'per_month', 'type_id', 'apply_from', 'apply_to', 'who_created', 'who_updated', 'when_created', 'when_updated', 'ip', 'status', 'active');
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
            $brandnameSmsSegmentConfig->version = $brandnameSmsSegmentConfig->version + 1;

            return $brandnameSmsSegmentConfig->save();
        }
        return false;
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

}