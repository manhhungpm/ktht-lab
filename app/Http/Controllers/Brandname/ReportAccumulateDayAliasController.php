<?php

namespace App\Http\Controllers\Brandname;

use App\Exports\Brandname\Report\AccumulateAliasExport;
use App\Exports\Brandname\Report\DayAliasExport;
use App\Http\Controllers\Controller;
use App\Repositories\Brandname\ReportAccumulateDayAliasRepository;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;


class ReportAccumulateDayAliasController extends Controller
{
    protected $brandnameReportAccumulateAlias;

    public function __construct(ReportAccumulateDayAliasRepository $brandnameReportAccumulateAlias)
    {
        $this->brandnameReportAccumulateAlias = $brandnameReportAccumulateAlias;
    }

    public function listingAlias(Request $request)
    {
        $searchParams = $request->only('from', 'to');

        $params = getDataTableRequestParams($request);
        $total = $this->brandnameReportAccumulateAlias->getList(
            $params['keyword'],
            $searchParams,
            true
        );


        $arr = array(
            'recordsTotal' => $total,
            'data' => $this->brandnameReportAccumulateAlias->getList(
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
        $export = new AccumulateAliasExport($searchParams);
        return $excel->download($export, trans('brandname.report.accumulate.alias.title').'.xlsx');
    }
}