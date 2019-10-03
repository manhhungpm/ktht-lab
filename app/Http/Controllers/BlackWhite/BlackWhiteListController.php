<?php

namespace App\Http\Controllers\BlackWhite;

use App\Exports\Blackwhite\ListExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\BlackWhite\BlackWhiteList\AddBlackWhiteListRequest;
use App\Http\Requests\BlackWhite\BlackWhiteList\EditBlackWhiteListRequest;
use App\Http\Requests\Common\IdsRequest;
use App\Http\Requests\Common\ImportRequest;
use App\Imports\ListImport;
use App\Repositories\BlackWhite\BlackWhiteListRepository;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;

class BlackWhiteListController extends Controller
{
    protected $_listRepository;
    protected $_excel;

    public function __construct(BlackWhiteListRepository $listRepository, Excel $excel)
    {
        $this->middleware('auth');
        $this->_listRepository = $listRepository;
        $this->_excel = $excel;
    }

    public function listing(Request $request)
    {
        $params = getDataTableRequestParams($request);
        $searchParams = $request->only('type', 'manager', 'provider', 'who_created', 'created_at', 'who_updated', 'updated_at');

        $total = $this->_listRepository->getList(
            $params['keyword'],
            $searchParams,
            true
        );

        $arr = array(
            'recordsTotal' => $total,
            'data' => $this->_listRepository->getList(
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

    public function add(AddBlackWhiteListRequest $request)
    {
        $result = $this->_listRepository->addAlias($request->only('alias', 'type', 'provider', 'manager', 'description', 'file'));

        return processCommonResponse($result);
    }

    public function edit(EditBlackWhiteListRequest $request)
    {
        $result = $this->_listRepository->editAlias($request->only('id', 'alias', 'type', 'provider', 'manager', 'description', 'file'));

        return processCommonResponse($result);
    }

    public function active(IdsRequest $request)
    {
        $result = $this->_listRepository->setActive($request->input('ids'));

        return processCommonResponse($result);
    }

    public function disable(IdsRequest $request)
    {
        $result = $this->_listRepository->setDisable($request->input('ids'));

        return processCommonResponse($result);
    }

    public function export(Request $request, Excel $excel)
    {
        $searchParams = $request->only('type', 'manager', 'provider', 'who_created', 'created_at', 'who_updated', 'updated_at');
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '0');
        $locale = $request->cookie('locale');
        \Illuminate\Support\Facades\App::setLocale($locale);
        $export = new ListExport($searchParams);
        return $excel->download($export, 'Kết quả tìm kiếm' . '.xlsx');
    }

    public function import(ImportRequest $request)
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '0');

        $whiteImport = new ListImport();
        $path = $request->file('file');
        $this->_excel->import($whiteImport, $path);

    }
}
