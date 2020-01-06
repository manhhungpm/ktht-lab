<?php

namespace App\Http\Controllers\Rent;

use App\Exports\Rent\RentExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Rent\EditRentRequest;
use App\Http\Requests\Rent\AddRentRequest;
use App\Repositories\Rent\RentRepository;
use Illuminate\Http\Request;
use App\Http\Requests\Common\IdRequest;
use Maatwebsite\Excel\Excel;

class RentController extends Controller
{
    protected $_rentRepository;

    public function __construct(RentRepository $rentRepository)
    {
        $this->middleware('auth');
        $this->_rentRepository = $rentRepository;
    }

    public function listing(Request $request)
    {
        $params = getDataTableRequestParams($request);

        $searchParams = $request->only('status', 'due_date', 'start_date', 'device_type');

        $total = $this->_rentRepository->getList(
            $params['keyword'],
            $searchParams,
            true
        );

        $arr = array(
            'recordsTotal' => $total,
            'data' => $this->_rentRepository->getList(
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

    public function add(AddRentRequest $request)
    {
        $result = $this->_rentRepository->addRent($request->only('user', 'description', 'date_range', 'device_type_id', 'amount', 'multi_device_details'), $request->ip());

        return processCommonResponse($result);
    }

    public function edit(EditRentRequest $request)
    {
        $result = $this->_rentRepository->editRent($request->only('user', 'description', 'date_range', 'device_type_id', 'id', 'amount', 'multi_device_details'), $request->ip());

        return processCommonResponse($result);
    }

    public function active(IdRequest $request)
    {
        $result = $this->_rentRepository->setActive($request->only('id'));

        return processCommonResponse($result);
    }

    public function disable(IdRequest $request)
    {
        $result = $this->_rentRepository->setDisable($request->only('id'));

        return processCommonResponse($result);
    }

    public function approved(IdRequest $request)
    {
        $result = $this->_rentRepository->setApproved($request->only('id'));

        return processCommonResponse($result);
    }

    public function export(Request $request, Excel $excel)
    {
        $searchParams = $request->only('status', 'device_type', 'device_type_name', 'start_date', 'due_date');
        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', '0');
        $locale = $request->cookie('locale');
        \Illuminate\Support\Facades\App::setLocale($locale);
        $export = new RentExport($searchParams);
        return $excel->download($export, 'Báo cáo mượn trả' . '.xlsx');
    }
}
