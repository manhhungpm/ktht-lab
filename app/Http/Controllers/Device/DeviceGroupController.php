<?php
/**
 * Created by PhpStorm.
 * User: manhhungpm
 * Date: 02/11/2019
 * Time: 22:18
 */

namespace App\Http\Controllers\Device;

use App\Exports\Device\DeviceGroup\DeviceGroupExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Device\DeviceGroup\AddDeviceGroupRequest;
use App\Http\Requests\Device\DeviceGroup\EditDeviceGroupRequest;
use App\Repositories\Device\DeviceGroupRepository;
use Illuminate\Http\Request;
use App\Http\Requests\Common\IdRequest;
use Maatwebsite\Excel\Excel;

class DeviceGroupController extends Controller
{
    protected $_deviceGroupRepository;

    public function __construct(DeviceGroupRepository $deviceGroupRepository)
    {
        $this->middleware('auth');
        $this->_deviceGroupRepository = $deviceGroupRepository;
    }

    public function listing(Request $request)
    {
        $params = getDataTableRequestParams($request);

        $searchParams = $request->only('status', 'provider');

        $total = $this->_deviceGroupRepository->getList(
            $params['keyword'],
            $searchParams,
            true
        );

        $arr = array(
            'recordsTotal' => $total,
            'data' => $this->_deviceGroupRepository->getList(
                $params['keyword'],
                $searchParams,
                false,
                $params['length'],
                $params['start'],
                $params['orderBy'],
                $params['orderType']
            ),
            'draw' => $params['draw'],
            'recordsFiltered' => $total
        );

        return response()->json($arr);
    }

    public function add(AddDeviceGroupRequest $request)
    {
        $result = $this->_deviceGroupRepository->addDeviceGroup($request->only('name', 'display_name', 'provider_id', 'description'),$request->ip());

        return processCommonResponse($result);
    }

    public function edit(EditDeviceGroupRequest $request)
    {
        $result = $this->_deviceGroupRepository->editDeviceGroup($request->only('name', 'display_name', 'provider_id', 'description', 'id'),$request->ip());

        return processCommonResponse($result);
    }

    public function active(IdRequest $request)
    {
        $result = $this->_deviceGroupRepository->setActive($request->only('id'));

        return processCommonResponse($result);
    }

    public function disable(IdRequest $request)
    {
        $result = $this->_deviceGroupRepository->setDisable($request->only('id'));

        return processCommonResponse($result);
    }

    public function listingAll(Request $request)
    {
        $length = 10;

        $page = $request->input('page');
        $search = $request->input('search');


        $data = $this->_deviceGroupRepository->listingAll(false, $search, $length, $page * $length);
        $total = $this->_deviceGroupRepository->listingAll(true, $search);

        return response()->json([
            'results' => $data,
            'total' => $total
        ]);
    }

    public function export(Request $request, Excel $excel)
    {
        $searchParams = $request->only('status', 'provider','provider_name');
        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', '0');
        $locale = $request->cookie('locale');
        \Illuminate\Support\Facades\App::setLocale($locale);
        $export = new DeviceGroupExport($searchParams);
        return $excel->download($export, 'Báo cáo nhóm thiết bị'.'.xlsx');
    }
}
