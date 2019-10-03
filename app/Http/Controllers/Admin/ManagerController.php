<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Manager\AddManagerRequest;
use App\Http\Requests\Admin\Manager\EditManagerRequest;
use App\Repositories\Admin\ManagerRepository;
use Illuminate\Http\Request;
use App\Http\Requests\Common\IdRequest;

class ManagerController extends Controller
{
    protected $managerRepository;

    public function __construct(ManagerRepository $managerRepository)
    {
        $this->middleware('auth');
        $this->managerRepository = $managerRepository;
    }

    public function listing(Request $request)
    {
        $params = getDataTableRequestParams($request);

        $total = $this->managerRepository->getList(
            $params['keyword'],
            true
        );

        $arr = array(
            'recordsTotal' => $total,
            'data' => $this->managerRepository->getList(
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

    public function add(AddManagerRequest $request)
    {
        $result = $this->managerRepository->addManager($request->only('name', 'description'));

        return processCommonResponse($result);
    }

    public function edit(EditManagerRequest $request)
    {
        $result = $this->managerRepository->editManager($request->only('name', 'description', 'id'));

        return processCommonResponse($result);
    }

    public function active(IdRequest $request)
    {
        $result = $this->managerRepository->setActive($request->only( 'id'));

        return processCommonResponse($result);
    }

    public function disable(IdRequest $request)
    {
        $result = $this->managerRepository->setDisable($request->only( 'id'));

        return processCommonResponse($result);
    }

    public function listingAll(Request $request)
    {
        $length = 10;

        $page = $request->input('page');
        $search = $request->input('search');


        $data = $this->managerRepository->listingAll(false, $search, $length, $page * $length);
        $total = $this->managerRepository->listingAll(true, $search);

        return response()->json([
            'results' => $data,
            'total' => $total
        ]);
    }

}