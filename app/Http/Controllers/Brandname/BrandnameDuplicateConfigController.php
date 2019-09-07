<?php

namespace App\Http\Controllers\Brandname;

use App\Http\Controllers\Controller;
use App\Http\Requests\Brandname\Config\DuplicateConfig\ChangeStatusConfigRequest;
use App\Http\Requests\Brandname\Config\DuplicateConfig\AddDuplicateConfigRequest;
use App\Http\Requests\Brandname\Config\DuplicateConfig\EditDuplicateConfigRequest;
use App\Http\Requests\Common\IdRequest;
use App\Repositories\Brandname\BrandnameDuplicateConfigRepository;
use Illuminate\Http\Request;


class BrandnameDuplicateConfigController extends Controller
{
    /**
     * @var SpamPatternsRepository
     */
    protected $brandnameDuplicateConfigRepository;

    function __construct(BrandnameDuplicateConfigRepository $brandnameDuplicateConfigRepository)
    {
        $this->middleware('auth');

        $this->brandnameDuplicateConfigRepository = $brandnameDuplicateConfigRepository;
    }


    public function edit(EditDuplicateConfigRequest $request)
    {

        $result = $this->brandnameDuplicateConfigRepository->edit($request->only('id', 'high_warning_from', 'high_warning_to', 'danger_warning_from', 'danger_warning_to', 'crisis_warning_from', 'crisis_warning_to', 'type_id', 'apply_from', 'apply_to', 'active'), $request->ip());

        return processCommonResponse($result);
    }

    public function add(AddDuplicateConfigRequest $request)
    {

        $result = $this->brandnameDuplicateConfigRepository->add($request->only('high_warning_from', 'high_warning_to', 'danger_warning_from', 'danger_warning_to', 'crisis_warning_from', 'crisis_warning_to', 'type_id', 'apply_from', 'apply_to'), $request->ip());

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

}
