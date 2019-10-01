<?php
/**
 * Created by PhpStorm.
 * User: hungnm
 * Date: 6/29/2019
 * Time: 2:49 PM
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Role\AddRoleRequest;
use App\Http\Requests\Admin\Role\EditRoleRequest;
use App\Repositories\Admin\RoleRepository;
use Illuminate\Http\Request;
use App\Http\Requests\Common\IdRequest;


class RoleController extends Controller
{
    protected $_roleRepository;

    function __construct(RoleRepository $roleRepository)
    {
        $this->middleware('auth');
        $this->_roleRepository = $roleRepository;
    }

    public function listing(Request $request)
    {
        $params = getDataTableRequestParams($request);

        $total = $this->_roleRepository->getList(
            $params['keyword'],
            true
        );

        $arr = array(
            'recordsTotal' => $total,
            'data' => $this->_roleRepository->getList(
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

    public function add(AddRoleRequest $request)
    {
        $result = $this->_roleRepository->addRole($request->only('name', 'description'));

        return processCommonResponse($result);
    }

    public function edit(EditRoleRequest $request)
    {
        $result = $this->_roleRepository->editRole($request->only('name', 'description', 'id'));

        return processCommonResponse($result);
    }

    public function active(IdRequest $request)
    {
        $result = $this->_roleRepository->setActive($request->only('id'));

        return processCommonResponse($result);
    }

    public function disable(IdRequest $request)
    {
        $result = $this->_roleRepository->setDisable($request->only('id'));

        return processCommonResponse($result);
    }

    public function listingAll(Request $request)
    {
        $length = 10;

        $page = $request->input('page');
        $search = $request->input('search');


        $data = $this->_roleRepository->listingAll(false, $search, $length, $page * $length);
        $total = $this->_roleRepository->listingAll(true, $search);

        return response()->json([
            'results' => $data,
            'total' => $total
        ]);
    }
}