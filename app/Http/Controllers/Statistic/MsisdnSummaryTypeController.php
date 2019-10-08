<?php

namespace App\Http\Controllers\Statistic;

use App\Http\Controllers\Controller;
use App\Http\Requests\Phone\PhoneLabelRequest;
use App\Repositories\Statistic\MsisdnSummaryTypeRepository;
use Illuminate\Http\Request;

class MsisdnSummaryTypeController extends Controller
{
    protected $msisdnSummaryTypeRepository;
    public function __construct(MsisdnSummaryTypeRepository $msisdnSummaryTypeRepository)
    {
        $this->middleware('auth');
        $this->msisdnSummaryTypeRepository = $msisdnSummaryTypeRepository;
    }

    public function listing(Request $request)
    {
        $params = getDataTableRequestParams($request);
        $searchParams = $request->only('duration_type_id','status','msisdn','carrier');

        $total = $this->msisdnSummaryTypeRepository->getList(
            $params['keyword'],
            $searchParams,
            true
        );

        $arr = array(
            'recordsTotal' => $total,
            'data' => $this->msisdnSummaryTypeRepository->getList(
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
    public function label(PhoneLabelRequest $request){
        $result = $this->msisdnSummaryTypeRepository->setLabel($request->only('phone','status'));
        return processCommonResponse($result);
    }

    public function labelMultiple(PhoneLabelRequest $request){
        $result = $this->msisdnSummaryTypeRepository->setLabelMultiple($request->only('phone','status'));
        return processCommonResponse($result);
    }

    public function getTotal(Request $request){
        $searchParams = $request->only('duration_type_id','status','msisdn','carrier');
        $data = $this->msisdnSummaryTypeRepository->getTotal($searchParams);
        if($data){
            return processCommonResponse(true, $data);
        }
        else {
            return processCommonResponse(false);
        }
    }
}