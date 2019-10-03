<?php

namespace App\Http\Controllers\Statistic;

use App\Http\Controllers\Controller;
use App\Repositories\Statistic\MsisdnSummaryTypeRepository;
use Illuminate\Http\Request;

class MsisdnSummaryTypeController extends Controller
{
    protected $_msisdnSummaryTypeRepository;
    public function __construct(MsisdnSummaryTypeRepository $msisdnSummaryTypeRepository)
    {
        $this->middleware('auth');
        $this->_msisdnSummaryTypeRepository = $msisdnSummaryTypeRepository;
    }

    public function listing(Request $request)
    {
        $params = getDataTableRequestParams($request);
        $searchParams = $request->only('duration_type_id');

        $total = $this->_msisdnSummaryTypeRepository->getList(
            $params['keyword'],
            $searchParams,
            true
        );

        $arr = array(
            'recordsTotal' => $total,
            'data' => $this->_msisdnSummaryTypeRepository->getList(
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
}