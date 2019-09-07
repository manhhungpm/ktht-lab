<?php

namespace App\Http\Controllers\Brandname;

use App\Http\Controllers\Controller;
use App\Repositories\Brandname\BrandnameSegmentConfigHistoryRepository;
use Illuminate\Http\Request;


class BrandnameSegmentConfigHistoryController extends Controller
{
    protected $brandnameSegmentConfigHistoryRepository;

    public function __construct(BrandnameSegmentConfigHistoryRepository $brandnameSegmentConfigHistoryRepository)
    {
        $this->middleware('auth');

        $this->brandnameSegmentConfigHistoryRepository = $brandnameSegmentConfigHistoryRepository;
    }

    public function listing(Request $request)
    {
        $params = getDataTableRequestParams($request);

        $searchParams = $request->input('config_id');


        $total = $this->brandnameSegmentConfigHistoryRepository->getList(
            $params['keyword'],
            $searchParams,
            true
        );

        $arr = array(
            'recordsTotal' => $total,
            'data' => $this->brandnameSegmentConfigHistoryRepository->getList(
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