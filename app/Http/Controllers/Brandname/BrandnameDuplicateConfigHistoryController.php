<?php

namespace App\Http\Controllers\Brandname;

use App\Http\Controllers\Controller;

use App\Repositories\Brandname\BrandnameDuplicateConfigHistoryRepository;
use Illuminate\Http\Request;


class BrandnameDuplicateConfigHistoryController extends Controller
{
    protected $brandnameDuplicateConfigHistoryRepository;

    public function __construct(BrandnameDuplicateConfigHistoryRepository $brandnameDuplicateConfigHistoryRepository)
    {
        $this->middleware('auth');

        $this->brandnameDuplicateConfigHistoryRepository = $brandnameDuplicateConfigHistoryRepository;
    }

    public function listing(Request $request)
    {
        $params = getDataTableRequestParams($request);

        $searchParams = $request->input('config_id');

        $total = $this->brandnameDuplicateConfigHistoryRepository->getList(
            $params['keyword'],
            $searchParams,
            true
        );

        $arr = array(
            'recordsTotal' => $total,
            'data' => $this->brandnameDuplicateConfigHistoryRepository->getList(
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