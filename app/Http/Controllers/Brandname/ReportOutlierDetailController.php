<?php

namespace App\Http\Controllers\Brandname;

use App\Http\Controllers\Controller;
use App\Repositories\Brandname\BrandnameOutlierDetailRepository;
use Illuminate\Http\Request;

class ReportOutlierDetailController extends Controller
{
    protected $brandnameOutlierDetailRepository;

    public function __construct(BrandnameOutlierDetailRepository $brandnameOutlierDetailRepository)
    {
        $this->brandnameOutlierDetailRepository = $brandnameOutlierDetailRepository;
    }

    public function listing(Request $request)
    {
        $params = getDataTableRequestParams($request);

        $total = $this->brandnameOutlierDetailRepository->getList(
            $params['keyword'],
            true
        );

        $arr = array(
            'recordsTotal' => $total,
            'data' => $this->brandnameOutlierDetailRepository->getList(
                $params['keyword'],
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