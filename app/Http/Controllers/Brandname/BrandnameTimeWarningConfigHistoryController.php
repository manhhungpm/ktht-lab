<?php

namespace App\Http\Controllers\Brandname;

use App\Http\Controllers\Controller;
use App\Repositories\Brandname\BrandnameTimeWarningConfigHistoryRepository;
use Illuminate\Http\Request;

class BrandnameTimeWarningConfigHistoryController extends Controller
{
    protected $brandnameWarningConfigHistoryRepository;

    function __construct(BrandnameTimeWarningConfigHistoryRepository $brandnameTimeWarningConfigHistoryRepository)
    {
        $this->middleware('auth');

        $this->brandnameWarningConfigHistoryRepository = $brandnameTimeWarningConfigHistoryRepository;
    }

    public function listing(Request $request)
    {
        $params = getDataTableRequestParams($request);

        $searchParams = $request->input('config_id');

        $total = $this->brandnameWarningConfigHistoryRepository->getList(
            $params['keyword'],
            $searchParams,
            true
        );

        $arr = array(
            'recordsTotal' => $total,
            'data' => $this->brandnameWarningConfigHistoryRepository->getList(
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