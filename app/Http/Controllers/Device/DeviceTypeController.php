<?php
/**
 * Created by PhpStorm.
 * User: manhhungpm
 * Date: 05/11/2019
 * Time: 22:52
 */

namespace App\Http\Controllers\Device;

use App\Exports\Device\DeviceType\DeviceTypeExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Common\ImportRequest;
use App\Imports\DeviceType\DeviceTypeImport;
use App\Repositories\Device\DeviceTypeRepository;
use Illuminate\Http\Request;
use App\Http\Requests\Common\IdRequest;
use Maatwebsite\Excel\Excel;

class DeviceTypeController extends Controller
{
    protected $_deviceTypeRepository;
    protected $_excel;

    public function __construct(DeviceTypeRepository $deviceTypeRepository,Excel $excel)
    {
        $this->middleware('auth');
        $this->_deviceTypeRepository = $deviceTypeRepository;
        $this->_excel = $excel;
    }

    public function listing(Request $request)
    {
        $params = getDataTableRequestParams($request);

        $searchParams = $request->only('status', 'device_group', 'store');


        $total = $this->_deviceTypeRepository->getList(
            $params['keyword'],
            $searchParams,
            true
        );

        $arr = array(
            'recordsTotal' => $total,
            'data' => $this->_deviceTypeRepository->getList(
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

    public function add(Request $request)
    {
        $result = $this->_deviceTypeRepository->addDeviceType($request->only('name', 'display_name', 'amount', 'store_id', 'device_group_id', 'description'), $request->ip());

        return processCommonResponse($result);
    }

    public function edit(Request $request)
    {
        $result = $this->_deviceTypeRepository->editDeviceType($request->only('name', 'display_name', 'amount', 'store_id', 'device_group_id', 'description', 'id'), $request->ip());

        return processCommonResponse($result);
    }

    public function active(IdRequest $request)
    {
        $result = $this->_deviceTypeRepository->setActive($request->only('id'));

        return processCommonResponse($result);
    }

    public function disable(IdRequest $request)
    {
        $result = $this->_deviceTypeRepository->setDisable($request->only('id'));

        return processCommonResponse($result);
    }

    public function listingAll(Request $request)
    {
        $length = 10;

        $page = $request->input('page');
        $search = $request->input('search');


        $data = $this->_deviceTypeRepository->listingAll(false, $search, $length, $page * $length);
        $total = $this->_deviceTypeRepository->listingAll(true, $search);

        return response()->json([
            'results' => $data,
            'total' => $total
        ]);
    }

    public function export(Request $request, Excel $excel)
    {
        $searchParams = $request->only('status', 'store', 'store_name', 'device_group', 'device_group_name');
        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', '0');
        $locale = $request->cookie('locale');
        \Illuminate\Support\Facades\App::setLocale($locale);
        $export = new DeviceTypeExport($searchParams);
        return $excel->download($export, 'Báo cáo loại thiết bị' . '.xlsx');
    }

    public function import(ImportRequest $request)
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '0');

        $deviceTypeImport = new DeviceTypeImport();
        $path = $request->file('file');
        $this->_excel->import($deviceTypeImport, $path);

    }
}