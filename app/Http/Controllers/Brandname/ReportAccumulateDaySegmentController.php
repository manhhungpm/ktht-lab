<?php

namespace App\Http\Controllers\Brandname;

use App\Http\Controllers\Controller;
use App\Repositories\Brandname\BrandnameAccumulateDaySegmentRepository;
use App\Exports\Brandname\Report\AccumulateDaySegmentExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;

class ReportAccumulateDaySegmentController extends Controller{

    protected $brandnameSegmentRepository;

    public function __construct(BrandnameAccumulateDaySegmentRepository $brandnameAccumulateDaySegmentRepository)
    {
        $this->brandnameSegmentRepository = $brandnameAccumulateDaySegmentRepository;
    }

    public function listing(Request $request){
        $searchParams = $request->only('from', 'to');

        $params = getDataTableRequestParams($request);
        $total = $this->brandnameSegmentRepository->getList(
            $params['keyword'],
            $searchParams,
            true
        );


        $arr = array(
            'recordsTotal' => $total,
            'data' => $this->brandnameSegmentRepository->getList(
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

    public function export(Request $request, Excel $excel){
        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', '0');
        $locale = $request->cookie('locale');
        \Illuminate\Support\Facades\App::setLocale($locale);
        $searchParams = $request->only('from', 'to');
        $export = new AccumulateDaySegmentExport($searchParams);
        return $excel->download($export, trans('brandname.report.accumulate.segment.title').'.xlsx');
    }
}