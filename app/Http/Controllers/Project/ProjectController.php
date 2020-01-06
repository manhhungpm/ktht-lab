<?php
/**
 * Created by PhpStorm.
 * User: manhhungpm
 * Date: 09/11/2019
 * Time: 23:52
 */

namespace App\Http\Controllers\Project;

use App\Exports\Project\ProjectExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Project\AddProjectRequest;
use App\Http\Requests\Project\EditProjectRequest;
use App\Repositories\Project\ProjectRepository;
use Illuminate\Http\Request;
use App\Http\Requests\Common\IdRequest;
use Maatwebsite\Excel\Excel;


class ProjectController extends Controller
{
    protected $_projectRepository;

    public function __construct(ProjectRepository $projectRepository)
    {
        $this->middleware('auth');
        $this->_projectRepository = $projectRepository;
    }

    public function listing(Request $request)
    {
        $params = getDataTableRequestParams($request);

        $searchParams = $request->only('status', 'user', 'device_type');

        $total = $this->_projectRepository->getList(
            $params['keyword'],
            $searchParams,
            true
        );

        $arr = array(
            'recordsTotal' => $total,
            'data' => $this->_projectRepository->getList(
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

    public function add(AddProjectRequest $request)
    {
        $result = $this->_projectRepository->addProject($request->only('name', 'user_id', 'device_type_id', 'amount', 'description', 'multi_device_details'), $request->ip());

        return processCommonResponse($result);
    }

    public function edit(EditProjectRequest $request)
    {
        $result = $this->_projectRepository->editProject($request->only('name', 'description', 'user_id', 'device_type_id', 'amount', 'id', 'multi_device_details'), $request->ip());

        return processCommonResponse($result);
    }

    public function active(IdRequest $request)
    {
        $result = $this->_projectRepository->setActive($request->only('id'));

        return processCommonResponse($result);
    }

    public function disable(IdRequest $request)
    {
        $result = $this->_projectRepository->setDisable($request->only('id'));

        return processCommonResponse($result);
    }

    public function listingAll(Request $request)
    {
        $length = 10;

        $page = $request->input('page');
        $search = $request->input('search');


        $data = $this->_projectRepository->listingAll(false, $search, $length, $page * $length);
        $total = $this->_projectRepository->listingAll(true, $search);

        return response()->json([
            'results' => $data,
            'total' => $total
        ]);
    }

    public function export(Request $request, Excel $excel)
    {
        $searchParams = $request->only('status', 'user', 'device_type', 'device_type_name', 'user_name');
        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', '0');
        $locale = $request->cookie('locale');
        \Illuminate\Support\Facades\App::setLocale($locale);
        $export = new ProjectExport($searchParams);
        return $excel->download($export, 'Báo cáo dự án' . '.xlsx');
    }
}
