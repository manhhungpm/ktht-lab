<?php
/**
 * Created by PhpStorm.
 * User: hungnm
 * Date: 6/24/2019
 * Time: 4:30 PM
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\ActiveUserRequest;
use App\Http\Requests\Admin\User\AddUserRequest;
use App\Http\Requests\Admin\User\DisableUserRequest;
use App\Http\Requests\Admin\User\EditUserRequest;
use App\Http\Requests\Common\IdRequest;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userRepository;

    function __construct(UserRepository $userRepository)
    {
//        $this->middleware('auth');
        $this->userRepository = $userRepository;
    }

    public function listing(Request $request)
    {
        $params = getDataTableRequestParams($request);
        $searchParams = $request->only('status', 'name', 'role');

        $total = $this->userRepository->getList(
            $params['keyword'],
            $searchParams,
            true
        );

        $arr = array(
            'recordsTotal' => $total,
            'data' => $this->userRepository->getList(
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

    public function disable(IdRequest $request)
    {
        $result = $this->userRepository->setDisable($request->only('id'));

        return processCommonResponse($result);
    }

    public function active(IdRequest $request)
    {
        $result = $this->userRepository->setActive($request->only('id'));

        return processCommonResponse($result);
    }

    public function add(AddUserRequest $request)
    {
        $result = $this->userRepository->addUser($request->only('name', 'display_name', 'email', 'mobile_phone', 'role', 'expired_at', 'active', 'who_created'));

        return processCommonResponse($result);
    }

    public function edit(EditUserRequest $request)
    {
        $result = $this->userRepository->editUser($request->only('id', 'display_name', 'email', 'mobile_phone', 'role', 'expired_at', 'who_updated'));

        return processCommonResponse($result);
    }

    public function listingAll(Request $request)
    {
        $length = 10;

        $page = $request->input('page');
        $search = $request->input('search');


        $data = $this->userRepository->listingAll(false, $search, $length, $page * $length);
        $total = $this->userRepository->listingAll(true, $search);

        return response()->json([
            'results' => $data,
            'total' => $total
        ]);
    }
}