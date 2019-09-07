<?php

namespace App\Http\Controllers\Brandname;

use App\Http\Controllers\Controller;
use App\Repositories\Brandname\BrandnameWrongTimeRepository;
use Illuminate\Http\Request;

class ReportWrongTimeController extends Controller
{
    protected $brandnameWrongTimeRepository;

    public function __construct(BrandnameWrongTimeRepository $brandnameWrongTimeRepository)
    {
        $this->brandnameWrongTimeRepository = $brandnameWrongTimeRepository;
    }

    public function listing(Request $request)
    {
        $params = getDataTableRequestParams($request);

        $total = $this->brandnameWrongTimeRepository->getList(
            $params['keyword'],
            true
        );

        $arr = array(
            'recordsTotal' => $total,
            'data' => $this->brandnameWrongTimeRepository->getList(
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