<?php

namespace App\Http\Controllers\Brandname;

use App\Http\Controllers\Controller;
use App\Http\Requests\Brandname\BrandnameList\ActiveBrandnameRequest;
use App\Http\Requests\Brandname\BrandnameList\AddBrandnameRequest;
use App\Http\Requests\Brandname\BrandnameList\DisableBrandnameRequest;
use App\Http\Requests\Brandname\BrandnameList\EditBrandnameRequest;
use App\Repositories\Brandname\ListRepository;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;

use App\Exports\Brandname\BrandnameList\ListExport;


class ListController extends Controller
{
    protected $listRepository;

    public function __construct(ListRepository $listRepository)
    {
        $this->listRepository = $listRepository;
    }

    public function listing(Request $request)
    {
        $params = getDataTableRequestParams($request);
        $searchParams = $request->only('brandname', 'br_type', 'status');

        $total = $this->listRepository->getList(
            $params['keyword'],
            $searchParams,
            true
        );

        $arr = array(
            'recordsTotal' => $total,
            'data' => $this->listRepository->getList(
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

    public function disable(Request $request)
    {
        $result = $this->listRepository->setDisable($request->input('ids'));

        return processCommonResponse($result);
    }

    public function active(Request $request)
    {
        $result = $this->listRepository->setActive($request->input('ids'));

        return processCommonResponse($result);
    }

    public function add(AddBrandnameRequest $request)
    {
        $result = $this->listRepository->addBrandname($request->only('brandname', 'br_type', 'reason', 'active', 'who_created'));

        return processCommonResponse($result);
    }

    public function edit(EditBrandnameRequest $request)
    {
        $result = $this->listRepository->editBrandname($request->only('id', 'brandname', 'br_type', 'reason', 'who_updated'));

        return processCommonResponse($result);
    }

    public function export(Request $request, Excel $excel)
    {
        $searchParams = $request->only('brandname', 'br_type', 'status');
        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', '0');
        $locale = $request->cookie('locale');
        \Illuminate\Support\Facades\App::setLocale($locale);
        $export = new ListExport($searchParams);
        return $excel->download($export, trans('brandname.list.title').'.xlsx');
    }
}