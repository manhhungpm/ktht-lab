<?php

namespace App\Repositories\Widget;

use App\Models\DeviceGroup;
use App\Models\DeviceRent;
use App\Models\DeviceType;
use App\Models\Project;
use App\Models\Provider;
use App\Models\Rent;
use App\Repositories\BaseRepository;

class WidgetRepository extends BaseRepository
{
    public function model()
    {
        return Provider::class;
    }

    public function getData()
    {
        $grid = [];
        //Thiết bị
        $grid['total_device_type'] = DeviceType::count();
        $grid['has_rent_device'] = DeviceRent::select('amount')->get()->sum('amount');
        $idWarning = Rent::select('id')->whereDate('due_date','<=',date('Y-m-d H:i:s'))->where('status',1)->get()->toArray();
        $grid['has_rent_device_warning'] = DeviceRent::whereIn('rent_id',$idWarning)->get()->sum('amount');

        //Dự án
        $grid['total_project'] = Project::count();
        $grid['has_doing_project'] = Project::where('status',ACTIVE)->get()->count();

        //Nhóm thiết bị
        $grid['total_device_group'] = DeviceGroup::count();

        //Nhà cung cấp
        $grid['total_provider'] = Provider::count();

        return collect($grid);
    }
}