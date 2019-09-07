<?php
/**
 * Created by PhpStorm.
 * User: hungnm
 * Date: 7/10/2019
 * Time: 8:43 AM
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ManageApi\ApiAccount\ActiveApiAccountRequest;
use App\Http\Requests\Admin\ManageApi\ApiAccount\AddApiAccountRequest;
use App\Http\Requests\Admin\ManageApi\ApiAccount\DisableApiAccountRequest;
use App\Http\Requests\Admin\ManageApi\ApiAccount\EditApiAccountRequest;
use App\Http\Requests\Admin\ManageApi\ApiAccount\ResetKeyRequest;
use App\Http\Requests\Common\IdRequest;
use Illuminate\Http\Request;
use App\Repositories\Admin\ApiAccountRepository;

class ApiAccountController extends Controller
{
    protected $apiAccountRepository;

    public function __construct(ApiAccountRepository $apiAccountRepository)
    {
        $this->apiAccountRepository = $apiAccountRepository;
    }

    public function listing(Request $request)
    {
        $params = getDataTableRequestParams($request);

        $total = $this->apiAccountRepository->getList(
            $params['keyword'],
            true
        );

        $arr = array(
            'recordsTotal' => $total,
            'data' => $this->apiAccountRepository->getList(
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

    public function disable(IdRequest $request)
    {
        $result = $this->apiAccountRepository->setDisable($request->only('id'));

        return processCommonResponse($result);
    }

    public function active(IdRequest $request)
    {
        $result = $this->apiAccountRepository->setActive($request->only('id'));

        return processCommonResponse($result);
    }

    public function add(AddApiAccountRequest $request)
    {
        $result = $this->apiAccountRepository->addAccountApi($request->only('name', 'api', 'active', 'who_created'));

        return processCommonResponse($result);
    }

    public function edit(EditApiAccountRequest $request)
    {
        $result = $this->apiAccountRepository->editAccountApi($request->only('id', 'api', 'who_updated'));

        return processCommonResponse($result);
    }

    public function resetKey(IdRequest $request){
        $result = $this->apiAccountRepository->resetPrivateKey($request->only('id'));

        return processCommonResponse($result);
    }
}