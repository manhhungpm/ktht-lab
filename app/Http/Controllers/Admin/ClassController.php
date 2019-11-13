<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\ClassRepository;
use Illuminate\Http\Request;
use App\Http\Requests\Common\IdRequest;

class ClassController extends Controller
{
    protected $_classRepository;

    public function __construct(ClassRepository $classRepository)
    {
        $this->middleware('auth');
        $this->_classRepository = $classRepository;
    }

    public function listing(Request $request)
    {
        $params = getDataTableRequestParams($request);

        $total = $this->_classRepository->getList(
            $params['keyword'],
            true
        );

        $arr = array(
            'recordsTotal' => $total,
            'data' => $this->_classRepository->getList(
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
        $result = $this->_classRepository->addClass($request->only('name', 'description','faculty_id'));

        return processCommonResponse($result);
    }

    public function edit(Request $request)
    {
        $result = $this->_classRepository->editClass($request->only('name', 'description','faculty_id', 'id'));

        return processCommonResponse($result);
    }

    public function active(IdRequest $request)
    {
        $result = $this->_classRepository->setActive($request->only('id'));

        return processCommonResponse($result);
    }

    public function disable(IdRequest $request)
    {
        $result = $this->_classRepository->setDisable($request->only('id'));

        return processCommonResponse($result);
    }

    public function listingAll(Request $request)
    {
        $length = 10;

        $page = $request->input('page');
        $search = $request->input('search');


        $data = $this->_classRepository->listingAll(false, $search, $length, $page * $length);
        $total = $this->_classRepository->listingAll(true, $search);

        return response()->json([
            'results' => $data,
            'total' => $total
        ]);
    }
}