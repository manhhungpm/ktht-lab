<?php

namespace App\Http\Controllers\Brandname;

use App\Http\Controllers\Controller;
use App\Http\Requests\Brandname\Config\DuplicateConfig\ChangeStatusConfigRequest;
use App\Http\Requests\Brandname\Config\Timeframe\AddTimeframeRequest;
use App\Http\Requests\Brandname\Config\Timeframe\EditTimeframeRequest;
use App\Http\Requests\Common\IdRequest;
use App\Repositories\Brandname\BrandnameTimeFrameConfigHistoryRepository;
use Illuminate\Http\Request;

class BrandnameTimeFrameConfigHistoryController extends Controller
{
    protected $brandnameTimeFrameConfigHistoryRepository;

    function __construct(BrandnameTimeFrameConfigHistoryRepository $brandnameTimeFrameConfigHistoryRepository)
    {
        $this->middleware('auth');

        $this->brandnameTimeFrameConfigHistoryRepository = $brandnameTimeFrameConfigHistoryRepository;
    }

    public function listing(Request $request)
    {
        $params = getDataTableRequestParams($request);

        $searchParams = $request->input('config_id');

        $total = $this->brandnameTimeFrameConfigHistoryRepository->getList(
            $params['keyword'],
            $searchParams,
            true
        );

        $arr = array(
            'recordsTotal' => $total,
            'data' => $this->brandnameTimeFrameConfigHistoryRepository->getList(
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