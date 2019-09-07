<?php

namespace App\Repositories\Brandname;

use App\Models\BrandnameSmsGroup;
use App\Models\BrandnameSmsType;
use App\Repositories\BaseRepository;

class SmsGroupRepository extends BaseRepository
{
    public function model()
    {
        return BrandnameSmsGroup::class;
    }

    public function getList($keyword = null, $counting = false, $limit = 10, $offset = 0, $orderBy = 'name', $orderType = 'desc')
    {
        $query = $this->model
            ->where('name', 'LIKE', "%$keyword%");

        if (!$counting) {
            $query->select('id', 'name', 'description', 'version', 'active', 'who_updated', 'when_updated', 'who_created', 'when_created');

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

    public function addSmsGroups($arr)
    {
        $query = $this->model;
        $arr['active'] = '0';
        $arr['version'] = '1';
        $arr['who_created'] = \auth()->user()->name;
        $query->fill($arr);
        return $query->save();
    }

    public function editSmsGroups($arr)
    {
        $query = $this->model->find($arr['id']);
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

    public function deleteSmsGroups($id)
    {
        $isExits = BrandnameSmsType::select('group_id')->where('group_id', $id)->get()->first();

        if ($isExits != null) {
            //ton tai
            return false;
        } else {
            //khong ton tai
//            $query = $this->model->where('id', $id)->delete();
            $query = $this->model->where('id', $id)->update(['active' => 0]);
            if ($query) {
                return true;
            }
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
//        $isExits = BrandnameSmsType::select('group_id')->where('group_id', $id)->where('active',ACTIVE)->get()->first();
        $isExits = BrandnameSmsType::select('group_id')->where('group_id', $id)->where('active',ACTIVE)->exists();

        if ($isExits) {
            //ton tai
            return CODE_ERROR_DISABLE_SMS_GROUP_WHEN_SMS_TYPE_ACTIVE;
        } else {
            //khong ton tai
            $query = $this->model->where('id', $id)->update(['active' => 0]);
            if ($query) {
                return true;
            }
            return false;
        }
    }

    public function listingAll($isCounting = false, $keyword = null, $limit = 10, $offset = 0)
    {
        $query = BrandnameSmsGroup::where('name', 'LIKE', "%$keyword%")->where('active',ACTIVE);
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