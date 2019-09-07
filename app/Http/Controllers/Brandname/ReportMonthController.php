<?php

namespace App\Http\Controllers\Brandname;

use App\Exports\Brandname\Report\AccumulateDaySegmentExport;
use App\Exports\Brandname\Report\MonthAliasExport;
use App\Exports\Brandname\Report\MonthSegmentExport;
use App\Http\Controllers\Controller;
use App\Repositories\Brandname\ReportMonthAliasRepository;
use App\Repositories\Brandname\ReportMonthSegmentRepository;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;


class ReportMonthController extends Controller
{
    protected $brandnameMonthAliasRepository;
    protected $brandnameMonthSegmentRepository;

    public function __construct(ReportMonthAliasRepository $brandnameMonthAliasRepository,ReportMonthSegmentRepository $brandnameMonthSegmentRepository)
    {
        $this->brandnameMonthAliasRepository = $brandnameMonthAliasRepository;
        $this->brandnameMonthSegmentRepository = $brandnameMonthSegmentRepository;
    }

    public function listingAlias(Request $request)
    {
        $searchParams = $request->only('time');

        $params = getDataTableRequestParams($request);
        $total = $this->brandnameMonthAliasRepository->getList(
            $params['keyword'],
            $searchParams,
            true
        );


        $arr = array(
            'recordsTotal' => $total,
            'data' => $this->brandnameMonthAliasRepository->getList(
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

    public function exportAlias(Request $request, Excel $excel)
    {
        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', '0');
        $locale = $request->cookie('locale');
        \Illuminate\Support\Facades\App::setLocale($locale);
        $searchParams = $request->only('time');
        $export = new MonthAliasExport($searchParams);
        return $excel->download($export, 'alias_month_report.xlsx');
    }

    public function listingSegment(Request $request){
        $searchParams = $request->only('time');

        $params = getDataTableRequestParams($request);
        $total = $this->brandnameMonthSegmentRepository->getList(
            $params['keyword'],
            $searchParams,
            true
        );


        $arr = array(
            'recordsTotal' => $total,
            'data' => $this->brandnameMonthSegmentRepository->getList(
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

    public function exportSegment(Request $request, Excel $excel){
        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', '0');
        $locale = $request->cookie('locale');
        \Illuminate\Support\Facades\App::setLocale($locale);
        $searchParams = $request->only('time');
        $export = new MonthSegmentExport($searchParams);
        return $excel->download($export, 'segment_month_day_report.xlsx');
    }
}