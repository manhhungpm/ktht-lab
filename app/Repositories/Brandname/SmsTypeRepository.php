<?php

namespace App\Repositories\Brandname;

use App\Models\BrandnameSmsType;

use App\Models\BrandnameTimeWarningConfig;
use App\Models\BrandnameSegmentWarningConfig;
use App\Models\BrandnameSmsDuplicateConfig;
use App\Models\BrandnameSmsTimeFrameConfig;
use App\Models\BrandnameSmsSegmentConfig;

use App\Repositories\BaseRepository;

class SmsTypeRepository extends BaseRepository
{
    public function model()
    {
        return BrandnameSmsType::class;
    }

    public function getList($keyword = null, $counting = false, $limit = 10, $offset = 0, $orderBy = 'name', $orderType = 'desc')
    {
        $query = $this->model
            ->where('name', 'LIKE', "%$keyword%")
        ;

        if (!$counting) {
            $query->with('brandnameSmsGroup')->select('id', 'name', 'description', 'version', 'alias', 'prefix', 'priority'
                , 'active', 'who_updated', 'when_updated', 'who_created', 'when_created','group_id');

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

    public function addSmsTypes($arr)
    {
        $query = $this->model;
        $arr['active'] = '0';
        $arr['version'] = '1';
        $this->model->group_id = $arr['group'];
        $arr['who_created'] = \auth()->user()->name;
        $query->fill($arr);
        return $query->save();
    }

    public function editSmsTypes($arr)
    {
        $query = $this->model->find($arr['id']);
        $query->group_id = $arr['group'];
        if ($query != null) {
            $arr['who_updated'] = \auth()->user()->name;
            $query->fill($arr);
            if ($query->save()) {
                return true;
            }
            return false;
        }
        return false;
    }

    public function deleteSmsTypes($id){

//        $query = $this->model->where('id', $id)->delete();
        $query = $this->model->where('id', $id)->update(['active' => 0]);

        if($query){
            return true;
        }
        else {
            return false;
        }
    }

    public function active($id)
    {
        $query = $this->model->where('id', $id);

        if ($query) {
            $query->update(['active' => 1]);
            return $query;
        }
    }

    public function disable($id)
    {

        $isExitBrandnameTimeWarning = BrandnameTimeWarningConfig::select('type_id')->where('type_id', $id)->where('active',ACTIVE)->exists();
        $isExitBrandnameTimeframe = BrandnameSmsTimeFrameConfig::select('type_id')->where('type_id', $id)->where('active',ACTIVE)->exists();
        $isExitBrandnameSegmentWarning = BrandnameSegmentWarningConfig::select('type_id')->where('type_id', $id)->where('active',ACTIVE)->exists();
        $isExitBrandnameSmsDuplicate = BrandnameSmsDuplicateConfig::select('type_id')->where('type_id', $id)->where('active',ACTIVE)->exists();
        $isExitBrandnameSmsSegment = BrandnameSmsSegmentConfig::select('type_id')->where('type_id', $id)->where('active',ACTIVE)->exists();

        if ($isExitBrandnameTimeWarning == true || $isExitBrandnameTimeframe == true ||$isExitBrandnameSegmentWarning  == true ||$isExitBrandnameSmsDuplicate == true ||
            $isExitBrandnameSmsSegment == true) {
            //ton tai
            return CODE_ERROR_DISABLE_SMS_TYPE_WHEN_CONFIG_ACTIVE;
        } else {
            //khong ton tai
            $query = $this->model->where('id', $id)->update(['active' => 0]);
            if ($query) {
                return true;
            }
            return false;
        }
    }

    public function listingSmsGroup($isCounting = false, $keyword = null,$smsGroupId = null, $limit = 10, $offset = 0)
    {
        $query = $this->model->select('id', 'name')
            ->where('name', 'LIKE', "%$keyword%");

        if ($smsGroupId != null && $smsGroupId != SELECT_All) {
            $query->where('group_id', $smsGroupId)->where('active',ACTIVE);
        }

        if ($smsGroupId == SELECT_All){
            $query->where('active',ACTIVE);
        }

        if (!$isCounting) {
            $query->skip($offset)
                ->take($limit)
                ->orderBy('group_id', 'asc');
        } else {
            return $query->count();
        }

        return $query->get();
    }
}