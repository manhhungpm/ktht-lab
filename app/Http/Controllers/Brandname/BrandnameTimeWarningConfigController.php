<?php

namespace App\Http\Controllers\Brandname;

use App\Http\Controllers\Controller;
use App\Http\Requests\Brandname\Config\TimeWarning\AddTimeWarningRequest;
use App\Http\Requests\Brandname\Config\TimeWarning\EditTimeWarningRequest;
use App\Http\Requests\Common\IdRequest;
use App\Repositories\Brandname\BrandnameTimeWarningConfigRepository;
use Illuminate\Http\Request;

class BrandnameTimeWarningConfigController extends Controller
{
    protected $brandnameWarningConfigRepository;

    function __construct(BrandnameTimeWarningConfigRepository $brandnameTimeFrameConfigRepository)
    {
        $this->middleware('auth');

        $this->brandnameWarningConfigRepository = $brandnameTimeFrameConfigRepository;
    }

    public function listing(Request $request)
    {
        $params = getDataTableRequestParams($request);

        $searchParams = [];


        $total = $this->brandnameWarningConfigRepository->getList(
            $params['keyword'],
            $searchParams,
            true
        );

        $arr = array(
            'recordsTotal' => $total,
            'data' => $this->brandnameWarningConfigRepository->getList(
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

    public function add(AddTimeWarningRequest $request)
    {

        $result = $this->brandnameWarningConfigRepository->add($request->only( 'time_frame', 'week_day', 'type_id', 'apply_from', 'apply_to',
            'high_warning_from','high_warning_to','danger_warning_from',
            'danger_warning_to','crisis_warning_from','crisis_warning_to'), $request->ip());

        return processCommonResponse($result);
    }

    public function edit(EditTimeWarningRequest $request)
    {
        $result = $this->brandnameWarningConfigRepository->edit($request->only('id', 'time_frame', 'week_day', 'type_id', 'apply_from', 'apply_to'
            ,'high_warning_from','high_warning_to','danger_warning_from',
            'danger_warning_to','crisis_warning_from','crisis_warning_to'), $request->ip());

        return processCommonResponse($result);
    }

    public function active(IdRequest $request)
    {

        $result = $this->brandnameWarningConfigRepository->active($request->input('id'),$request->input('sms_type_id'), $request->ip());

        return processCommonResponse($result);
    }

    public function inactive(IdRequest $request)
    {

        $result = $this->brandnameWarningConfigRepository->inactive($request->input('id'), $request->ip());

        return processCommonResponse($result);
    }
}
