<?php

namespace App\Repositories\Statistic;

use App\Models\DeviceRent;
use App\Models\DeviceType;
use App\Models\Project;
use App\Models\ProjectDevice;
use App\Models\Rent;
use App\Repositories\BaseRepository;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class StatisticRepository extends BaseRepository
{
    public function model()
    {
        // TODO: Implement model() method.
        return DeviceType::class;
    }

    public function getDataPie()
    {
        $grid = [];

        //Thiết bị còn lại
        $deviceRemain = DeviceType::select('amount')->get();
        $grid['device_remain'] = $deviceRemain->sum('amount');

        //Thiết bị đang mượn
        $deviceRent = DeviceRent::select('amount')->get();
        $grid['device_rent'] = $deviceRent->sum('amount');

        return collect($grid);

    }

    public function getDataColumn()
    {
        //Lấy tên project
        $grid = [];

        $projectName = Project::select('name')->get()->toArray();

        $grid['project_name'] = $projectName;

        //Lấy số lượng thiết bị mỗi project
        $projectId = Project::select('id')->get()->toArray();
        $arrProjectId = [];
        foreach ($projectId as $item) {
            array_push($arrProjectId, $item['id']);
        }

        $projectDevice = [];
        foreach ($arrProjectId as $key => $item) {
            array_push($projectDevice, ProjectDevice::select('device_id')->where('project_id', $item)->get()->sum('device_id'));
        }
        $grid['project_device'] = $projectDevice;

        return collect($grid);
    }

    public function getDataPieProject($timeFilter)
    {
        $grid = [];
//        dd($timeFilter);
        $projectDone = Project::select('status', 'created_at')
            ->where('status', ACTIVE)
            ->whereDate('created_at', '>=', $timeFilter['time_filter'][0])
            ->whereDate('created_at', '<=', $timeFilter['time_filter'][1])
            ->get()->count();

        $projectNotDone = Project::select('status', 'created_at')
            ->where('status', INACTIVE)
            ->whereDate('created_at', '>=', $timeFilter['time_filter'][0])
            ->whereDate('created_at', '<=', $timeFilter['time_filter'][1])
            ->get()->count();

        $grid['projectDone'] = $projectDone;
        $grid['projectNotDone'] = $projectNotDone;

        return collect($grid);
    }

    public function getDataColumnUserDevice($timeFilter)
    {
        $grid = [];

        $queryName = Rent::select('id', 'user_id', 'due_date', 'status', 'created_at')
            ->where('status', ACTIVE)
            ->where('due_date', '<=', date('Y-m-d H:i:s'))
            ->whereDate('created_at', '>=', $timeFilter['time_filter'][0])
            ->whereDate('created_at', '<=', $timeFilter['time_filter'][1])
            ->with('user')
            ->get()
            ->toArray();

        $grid['name'] = array_map(function ($item) {
            return $item['user']['name'];
        }, $queryName);

        //Lấy số lượng các thiết bị của ng dùng đó quá hạn
        $queryIdRent = Rent::select('id', 'due_date', 'status', 'created_at')
            ->where('status', ACTIVE)
            ->where('due_date', '<=', date('Y-m-d H:i:s'))
            ->whereDate('created_at', '>=', $timeFilter['time_filter'][0])
            ->whereDate('created_at', '<=', $timeFilter['time_filter'][1])
            ->get()
            ->toArray();

        $arrIdRent = array_map(function ($item) {
            return $item['id'];
        }, $queryIdRent);

        $countDevice = [];
        foreach ($arrIdRent as $key => $item) {
            $count = DeviceRent::select('rent_id','amount')->where('rent_id',$item)->get()->sum('amount');
            array_push($countDevice,$count);
        }
//        dd($countDevice);
        $grid['countDevice'] = $countDevice;

        return collect($grid);
    }

}