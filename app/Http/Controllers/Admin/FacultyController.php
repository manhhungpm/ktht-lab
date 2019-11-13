<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\FacultyRepository;
use Illuminate\Http\Request;
use App\Http\Requests\Common\IdRequest;

class FacultyController extends Controller
{
    protected $_facultyRepository;

    public function __construct(FacultyRepository $facultyRepository)
    {
        $this->middleware('auth');
        $this->_facultyRepository = $facultyRepository;
    }

    public function listing(Request $request)
    {
        $params = getDataTableRequestParams($request);

        $total = $this->_facultyRepository->getList(
            $params['keyword'],
            true
        );

        $arr = array(
            'recordsTotal' => $total,
            'data' => $this->_facultyRepository->getList(
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
        $result = $this->_facultyRepository->addFaculty($request->only('name', 'description'));

        return processCommonResponse($result);
    }

    public function edit(Request $request)
    {
        $result = $this->_facultyRepository->editFaculty($request->only('name', 'description', 'id'));

        return processCommonResponse($result);
    }

    public function active(IdRequest $request)
    {
        $result = $this->_facultyRepository->setActive($request->only('id'));

        return processCommonResponse($result);
    }

    public function disable(IdRequest $request)
    {
        $result = $this->_facultyRepository->setDisable($request->only('id'));

        return processCommonResponse($result);
    }

    public function listingAll(Request $request)
    {
        $length = 10;

        $page = $request->input('page');
        $search = $request->input('search');


        $data = $this->_facultyRepository->listingAll(false, $search, $length, $page * $length);
        $total = $this->_facultyRepository->listingAll(true, $search);

        return response()->json([
            'results' => $data,
            'total' => $total
        ]);
    }
}