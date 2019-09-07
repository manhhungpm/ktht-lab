<?php

namespace App\Http\Controllers\Brandname;

use App\Exports\Brandname\Report\DayAliasExport;
use App\Exports\Brandname\Report\DaySegmentExport;
use App\Http\Controllers\Controller;
use App\Repositories\Brandname\BrandnameAliasRepository;
use App\Repositories\Brandname\BrandnameSegmentRepository;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;


class ReportDayController extends Controller
{
    protected $brandnameAliasRepository;
    protected $brandnameSegmentRepository;

    public function __construct(BrandnameAliasRepository $brandnameAliasRepository, BrandnameSegmentRepository $brandnameSegmentRepository)
    {
        $this->brandnameAliasRepository = $brandnameAliasRepository;
        $this->brandnameSegmentRepository = $brandnameSegmentRepository;
    }

    public function listingAlias(Request $request)
    {
        $searchParams = $request->only('from', 'to');

        $params = getDataTableRequestParams($request);
        $total = $this->brandnameAliasRepository->getList(
            $params['keyword'],
            $searchParams,
            true
        );


        $arr = array(
            'recordsTotal' => $total,
            'data' => $this->brandnameAliasRepository->getList(
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
        $searchParams = $request->only('from', 'to');
        $export = new DayAliasExport($searchParams);
        return $excel->download($export, 'alias_day_report.xlsx');
    }

    public function listingSegment(Request $request){
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

    public function exportSegment(Request $request, Excel $excel)
    {
        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', '0');
        $locale = $request->cookie('locale');
        \Illuminate\Support\Facades\App::setLocale($locale);
        $searchParams = $request->only('from', 'to');
        $export = new DaySegmentExport($searchParams);
        return $excel->download($export, 'segment_day_report.xlsx');
    }
}