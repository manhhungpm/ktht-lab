<?php

namespace App\Http\Controllers\Brandname;

use App\Http\Controllers\Controller;
use App\Http\Requests\Brandname\Config\SegmentConfig\ChangeStatusConfigRequest;
use App\Http\Requests\Brandname\Config\SegmentConfig\AddSegmentConfigRequest;
use App\Http\Requests\Brandname\Config\SegmentConfig\EditSegmentConfigRequest;
use App\Http\Requests\Common\IdRequest;
use App\Repositories\Brandname\BrandnameSegmentConfigRepository;
use Illuminate\Http\Request;


class BrandnameSegmentConfigController extends Controller
{
    /**
     * @var SpamPatternsRepository
     */
    protected $brandnameSegmentConfigRepository;

    function __construct(BrandnameSegmentConfigRepository $brandnameSegmentConfigRepository)
    {
        $this->middleware('auth');

        $this->brandnameSegmentConfigRepository = $brandnameSegmentConfigRepository;
    }


    public function edit(EditSegmentConfigRequest $request)
    {

        $result = $this->brandnameSegmentConfigRepository->edit($request->only('id', 'name', 'per_day', 'per_month', 'type_id', 'apply_from', 'apply_to', 'active'), $request->ip());

        return processCommonResponse($result);
    }

    public function add(AddSegmentConfigRequest $request)
    {

        $result = $this->brandnameSegmentConfigRepository->add($request->only('name', 'per_day', 'per_month', 'type_id', 'apply_from', 'apply_to'), $request->ip());

        return processCommonResponse($result);
    }

    public function active(IdRequest $request)
    {

        $result = $this->brandnameSegmentConfigRepository->active($request->input('id'),$request->input('sms_type_id'), $request->ip());

        return processCommonResponse($result);
    }

    public function inactive(IdRequest $request)
    {

        $result = $this->brandnameSegmentConfigRepository->inactive($request->input('id'), $request->ip());

        return processCommonResponse($result);
    }

    public function listing(Request $request)
    {
        $params = getDataTableRequestParams($request);

        $searchParams = $request->only('name', 'per_day', 'per_month');


        $total = $this->brandnameSegmentConfigRepository->getList(
            $params['keyword'],
            $searchParams,
            true
        );

        $arr = array(
            'recordsTotal' => $total,
            'data' => $this->brandnameSegmentConfigRepository->getList(
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

}
