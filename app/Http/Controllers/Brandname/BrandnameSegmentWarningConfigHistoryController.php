<?php

namespace App\Http\Controllers\Brandname;

use App\Http\Controllers\Controller;
use App\Repositories\Brandname\BrandnameSegmentWarningConfigHistoryRepository;
use Illuminate\Http\Request;

class BrandnameSegmentWarningConfigHistoryController extends Controller
{
    protected $brandnameSegmentWarningConfigHistoryRepository;

    public function __construct(BrandnameSegmentWarningConfigHistoryRepository $brandnameSegmentWarningConfigHistoryRepository)
    {
        $this->middleware('auth');

        $this->brandnameSegmentWarningConfigHistoryRepository = $brandnameSegmentWarningConfigHistoryRepository;
    }

    public function listing(Request $request)
    {
        $params = getDataTableRequestParams($request);

        $searchParams = $request->input('config_id');


        $total = $this->brandnameSegmentWarningConfigHistoryRepository->getList(
            $params['keyword'],
            $searchParams,
            true
        );

        $arr = array(
            'recordsTotal' => $total,
            'data' => $this->brandnameSegmentWarningConfigHistoryRepository->getList(
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