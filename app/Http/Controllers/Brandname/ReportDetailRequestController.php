<?php

namespace App\Http\Controllers\Brandname;

use App\Http\Controllers\Controller;
use App\Repositories\Brandname\BrandnameDetailRequestRepository;
use App\Http\Requests\Brandname\DetailRequest\AddDetailRequest;

use Illuminate\Http\Request;

class ReportDetailRequestController extends Controller
{
    protected $brandnameDetailRequestRepository;

    public function __construct(BrandnameDetailRequestRepository $brandnameDetailRequestRepository)
    {
        $this->brandnameDetailRequestRepository = $brandnameDetailRequestRepository;
    }

    public function listing(Request $request)
    {
        $searchParams = $request->only('from', 'to', 'status','created_at');

        $params = getDataTableRequestParams($request);
        $total = $this->brandnameDetailRequestRepository->getList(
            $params['keyword'],
            $searchParams,
            true
        );


        $arr = array(
            'recordsTotal' => $total,
            'data' => $this->brandnameDetailRequestRepository->getList(
                $params['keyword'],
                $searchParams,
                false,
                $params['length'],
                $params['start'],
                $params['orderBy'],
                $params['orderType']
            ),
            'draw' => $params['draw'],
            'recordsFiltered' => $total,
        );

        return response()->json($arr);
    }

    public function add(AddDetailRequest $request)
    {
        $result = $this->brandnameDetailRequestRepository->addDetailRequest($request->only('phone', 'time'));

        return processCommonResponse($result);
    }

    public function resend(Request $request)
    {
        $result = $this->brandnameDetailRequestRepository->resendDetailRequest($request->only('params'));

        return processCommonResponse($result);
    }

    public function download(Request $request)
    {
        $result = $this->brandnameDetailRequestRepository->downloadDetailRequest($request->only('phone', 'time'));

        return processCommonResponse($result);
    }
}