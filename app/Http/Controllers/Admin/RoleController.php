<?php
/**
 * Created by PhpStorm.
 * User: hungnm
 * Date: 6/29/2019
 * Time: 2:49 PM
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\RoleRepository;
use Illuminate\Http\Request;

class RoleController extends Controller{
    protected $roleRepository;

    function __construct(RoleRepository $roleRepository)
    {
//        $this->middleware('auth');
        $this->roleRepository = $roleRepository ;
    }

    public function listing(Request $request){
        $params = getDataTableRequestParams($request);

        $total = $this->roleRepository->getList(
            $params['keyword'],
            true
        );

        $arr = array(
            'recordsTotal' => $total,
            'data' => $this->roleRepository->getList(
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