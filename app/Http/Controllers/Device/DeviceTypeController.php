<?php
/**
 * Created by PhpStorm.
 * User: manhhungpm
 * Date: 05/11/2019
 * Time: 22:52
 */

namespace App\Http\Controllers\Device;

use App\Http\Controllers\Controller;
use App\Repositories\Device\DeviceGroupRepository;
use App\Repositories\Device\DeviceTypeRepository;
use Illuminate\Http\Request;
use App\Http\Requests\Common\IdRequest;

class DeviceTypeController extends Controller
{
    protected $_deviceTypeRepository;

    public function __construct(DeviceTypeRepository $deviceTypeRepository)
    {
        $this->middleware('auth');
        $this->_deviceTypeRepository = $deviceTypeRepository;
    }

    public function listing(Request $request)
    {
        $params = getDataTableRequestParams($request);

        $total = $this->_deviceTypeRepository->getList(
            $params['keyword'],
            true
        );

        $arr = array(
            'recordsTotal' => $total,
            'data' => $this->_deviceTypeRepository->getList(
                $params['keyword'],
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
        $result = $this->_deviceTypeRepository->addDeviceType($request->only('name', 'display_name', 'amount', 'store_id', 'device_group_id', 'description'));

        return processCommonResponse($result);
    }

    public function edit(Request $request)
    {
        $result = $this->_deviceTypeRepository->editDeviceType($request->only('name', 'display_name', 'amount', 'store_id', 'device_group_id', 'description', 'id'));

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
}