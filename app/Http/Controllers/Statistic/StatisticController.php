<?php


namespace App\Http\Controllers\Statistic;

use App\Http\Controllers\Controller;
use App\Repositories\Statistic\StatisticRepository;
use Illuminate\Http\Request;

class StatisticController extends Controller
{
    protected $_statisticRepository;

    public function __construct(StatisticRepository $statisticRepository)
    {
        $this->middleware('auth');
        $this->_statisticRepository = $statisticRepository;
    }

    public function getDataDashboardPie()
    {
        $arr = [
            'code' => CODE_SUCCESS,
            'data' => $this->_statisticRepository->getDataPie()
        ];
        return response()->json($arr);
    }

    public function getDataDashboardColumn()
    {
        $arr = [
            'code' => CODE_SUCCESS,
            'data' => $this->_statisticRepository->getDataColumn()
        ];
        return response()->json($arr);
    }

    public function getDataStatsPie(Request $request)
    {
        $search = $request->only('time_filter');
        $arr = [
            'code' => CODE_SUCCESS,
            'data' => $this->_statisticRepository->getDataPieProject($search)
        ];
        return response()->json($arr);
    }

    public function getDataStatsColumn(Request $request)
    {
        $search = $request->only('time_filter');
        $arr = [
            'code' => CODE_SUCCESS,
            'data' => $this->_statisticRepository->getDataColumnUserDevice($search)
        ];
        return response()->json($arr);
    }


}