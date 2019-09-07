<?php
/**
 * Created by PhpStorm.
 * User: hungnm
 * Date: 7/9/2019
 * Time: 4:38 PM
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ManageApi\Api\ActiveApiRequest;
use App\Http\Requests\Admin\ManageApi\Api\AddApiRequest;
use App\Http\Requests\Admin\ManageApi\Api\DisableApiRequest;
use App\Http\Requests\Admin\ManageApi\Api\EditApiRequest;
use App\Http\Requests\Common\IdRequest;
use Illuminate\Http\Request;
use App\Repositories\Admin\ApiRepository;


class ApiController extends Controller
{
    protected $apiRepository;

    public function __construct(ApiRepository $apiRepository)
    {
        $this->apiRepository = $apiRepository;
    }

    public function listing(Request $request)
    {
        $params = getDataTableRequestParams($request);

        $total = $this->apiRepository->getList(
            $params['keyword'],
            true
        );

        $arr = array(
            'recordsTotal' => $total,
            'data' => $this->apiRepository->getList(
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
        $result = $this->apiRepository->setDisable($request->only('id'));

        return processCommonResponse($result);
    }

    public function active(IdRequest $request)
    {
        $result = $this->apiRepository->setActive($request->only('id'));

        return processCommonResponse($result);
    }

    public function add(AddApiRequest $request)
    {
        $result = $this->apiRepository->addApi($request->only('name', 'display_name', 'description', 'active', 'who_created'));

        return processCommonResponse($result);
    }

    public function edit(EditApiRequest $request)
    {
        $result = $this->apiRepository->editApi($request->only('id', 'display_name', 'description', 'who_updated'));

        return processCommonResponse($result);
    }
}