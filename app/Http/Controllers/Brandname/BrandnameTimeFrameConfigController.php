<?php

namespace App\Http\Controllers\Brandname;

use App\Http\Controllers\Controller;
use App\Http\Requests\Brandname\Config\DuplicateConfig\ChangeStatusConfigRequest;
use App\Http\Requests\Brandname\Config\Timeframe\AddTimeframeRequest;
use App\Http\Requests\Brandname\Config\Timeframe\EditTimeframeRequest;
use App\Http\Requests\Common\IdRequest;
use App\Repositories\Brandname\BrandnameTimeFrameConfigRepository;
use Illuminate\Http\Request;


class BrandnameTimeFrameConfigController extends Controller
{
    /**
     * @var
     */
    protected $brandnameTimeFrameConfigRepository;

    function __construct(BrandnameTimeFrameConfigRepository $brandnameTimeFrameConfigRepository)
    {
        $this->middleware('auth');

        $this->brandnameTimeFrameConfigRepository = $brandnameTimeFrameConfigRepository;
    }

    public function listing(Request $request)
    {
        $params = getDataTableRequestParams($request);

        $searchParams = [];


        $total = $this->brandnameTimeFrameConfigRepository->getList(
            $params['keyword'],
            $searchParams,
            true
        );

        $arr = array(
            'recordsTotal' => $total,
            'data' => $this->brandnameTimeFrameConfigRepository->getList(
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

    public function add(AddTimeframeRequest $request)
    {

        $result = $this->brandnameTimeFrameConfigRepository->add($request->only( 'time_frame', 'week_day', 'type_id', 'apply_from', 'apply_to'), $request->ip());

        return processCommonResponse($result);
    }

    public function edit(EditTimeframeRequest $request)
    {
        $result = $this->brandnameTimeFrameConfigRepository->edit($request->only('id', 'time_frame', 'week_day', 'type_id', 'apply_from', 'apply_to'), $request->ip());

        return processCommonResponse($result);
    }

    public function active(IdRequest $request)
    {
        $result = $this->brandnameTimeFrameConfigRepository->active($request->input('id'),$request->input('sms_type_id'), $request->ip());

        return processCommonResponse($result);
    }

    public function inactive(IdRequest $request)
    {
        $result = $this->brandnameTimeFrameConfigRepository->inactive($request->input('id'), $request->ip());

        return processCommonResponse($result);
    }

    public function changeStatus(ChangeStatusConfigRequest $request)
    {

        $result = $this->brandnameTimeFrameConfigRepository->changeStatus($request->only('id', 'status'), $request->ip());

        return processCommonResponse($result);
    }

    public function listingSmsType(Request $request)
    {
        $length = 10;

        $page = $request->input('page');
        $search = $request->input('search');


        $data = $this->brandnameTimeFrameConfigRepository->listingSmsType(false, $search, $length, $page * $length);
        $total = $this->brandnameTimeFrameConfigRepository->listingSmsType(true, $search);

        return response()->json([
            'results' => $data,
            'total' => $total
        ]);
    }

}
