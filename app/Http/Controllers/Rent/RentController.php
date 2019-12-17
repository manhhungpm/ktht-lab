<?php

namespace App\Http\Controllers\Rent;

use App\Http\Controllers\Controller;
use App\Repositories\Rent\RentRepository;
use Illuminate\Http\Request;
use App\Http\Requests\Common\IdRequest;

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

        $total = $this->_rentRepository->getList(
            $params['keyword'],
            true
        );

        $arr = array(
            'recordsTotal' => $total,
            'data' => $this->_rentRepository->getList(
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
        $result = $this->_rentRepository->addRent($request->only('user', 'description', 'date_range', 'device_type_id', 'amount'), $request->ip());

        return processCommonResponse($result);
    }

    public function edit(Request $request)
    {
        $result = $this->_rentRepository->editRent($request->only('user', 'description', 'date_range', 'device_type_id', 'id', 'amount'), $request->ip());

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
}