<?php

namespace App\Http\Controllers\Brandname;

use App\Http\Controllers\Controller;
use App\Http\Requests\Brandname\Config\SegmentWarningConfig\AddSegmentWarningConfigRequest;
use App\Http\Requests\Brandname\Config\SegmentWarningConfig\EditSegmentWarningConfigRequest;
use App\Http\Requests\Common\IdRequest;
use App\Repositories\Brandname\BrandnameSegmentWarningConfigRepository;
use Illuminate\Http\Request;


class BrandnameSegmentWarningConfigController extends Controller
{
    /**
     * @var SpamPatternsRepository
     */
    protected $brandnameDuplicateConfigRepository;

    function __construct(BrandnameSegmentWarningConfigRepository $brandnameDuplicateConfigRepository)
    {
        $this->middleware('auth');

        $this->brandnameDuplicateConfigRepository = $brandnameDuplicateConfigRepository;
    }


    public function edit(EditSegmentWarningConfigRequest $request)
    {

        $result = $this->brandnameDuplicateConfigRepository->edit($request->only('id', 'name', 'high_warning_from', 'high_warning_to', 'danger_warning_from', 'danger_warning_to', 'crisis_warning_from', 'crisis_warning_to', 'type_id', 'apply_from'
            , 'apply_to', 'ip', 'version', 'active', 'high_warning_from_m', 'high_warning_to_m', 'danger_warning_from_m', 'danger_warning_to_m', 'crisis_warning_from_m', 'crisis_warning_to_m', 'is_warning'), $request->ip());

        return processCommonResponse($result);
    }

    public function add(AddSegmentWarningConfigRequest $request)
    {

        $result = $this->brandnameDuplicateConfigRepository->add($request->only('name', 'high_warning_from', 'high_warning_to', 'danger_warning_from', 'danger_warning_to', 'crisis_warning_from', 'crisis_warning_to', 'type_id', 'apply_from'
            , 'apply_to', 'ip', 'version', 'active', 'high_warning_from_m', 'high_warning_to_m', 'danger_warning_from_m', 'danger_warning_to_m', 'crisis_warning_from_m', 'crisis_warning_to_m', 'is_warning'), $request->ip());

        return processCommonResponse($result);
    }

    public function active(IdRequest $request)
    {

        $result = $this->brandnameDuplicateConfigRepository->active($request->input('id'),$request->input('sms_type_id'), $request->ip());

        return processCommonResponse($result);
    }

    public function inactive(IdRequest $request)
    {

        $result = $this->brandnameDuplicateConfigRepository->inactive($request->input('id'), $request->ip());

        return processCommonResponse($result);
    }

    public function changeStatus(ChangeStatusConfigRequest $request)
    {

        $result = $this->brandnameDuplicateConfigRepository->changeStatus($request->only('id', 'status'), $request->ip());

        return processCommonResponse($result);
    }

    public function listing(Request $request)
    {
        $params = getDataTableRequestParams($request);

        $searchParams = [];


        $total = $this->brandnameDuplicateConfigRepository->getList(
            $params['keyword'],
            $searchParams,
            true
        );

        $arr = array(
            'recordsTotal' => $total,
            'data' => $this->brandnameDuplicateConfigRepository->getList(
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

    public function listingSmsType(Request $request)
    {
        $length = 10;

        $page = $request->input('page');
        $search = $request->input('search');


        $data = $this->brandnameDuplicateConfigRepository->listingSmsType(false, $search, $length, $page * $length);
        $total = $this->brandnameDuplicateConfigRepository->listingSmsType(true, $search);

        return response()->json([
            'results' => $data,
            'total' => $total
        ]);
    }

}
