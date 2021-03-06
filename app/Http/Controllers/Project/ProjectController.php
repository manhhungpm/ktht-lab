<?php
/**
 * Created by PhpStorm.
 * User: manhhungpm
 * Date: 09/11/2019
 * Time: 23:52
 */

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Repositories\Project\ProjectRepository;
use Illuminate\Http\Request;
use App\Http\Requests\Common\IdRequest;

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

        $total = $this->_projectRepository->getList(
            $params['keyword'],
            true
        );

        $arr = array(
            'recordsTotal' => $total,
            'data' => $this->_projectRepository->getList(
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

    public function add(Request $request)
    {
        $result = $this->_projectRepository->addProject($request->only('name', 'description', 'user_id', 'device_type_id', 'description'));

        return processCommonResponse($result);
    }

    public function edit(Request $request)
    {
        $result = $this->_projectRepository->editProject($request->only('name', 'description', 'user_id', 'device_type_id', 'id'));

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
}
