<?php
/**
 * Created by PhpStorm.
 * User: hungnm
 * Date: 7/11/2019
 * Time: 11:14 AM
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Config\AddConfigRequest;
use App\Http\Requests\Admin\Config\EditConfigRequest;
use App\Repositories\Admin\ConfigRepository;
use Illuminate\Http\Request;

class ConfigController extends Controller
{

    protected $configRepository;

    public function __construct(ConfigRepository $configRepository)
    {
        $this->configRepository = $configRepository;
    }

    public function listing(Request $request)
    {
        $params = getDataTableRequestParams($request);

        $total = $this->configRepository->getList(
            $params['keyword'],
            true
        );

        $arr = array(
            'recordsTotal' => $total,
            'data' => $this->configRepository->getList(
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

    public function add(AddConfigRequest $request){
        $result = $this->configRepository->addConfig($request->only('name', 'group_name', 'description', 'value', 'who_created'));

        return processCommonResponse($result);
    }

    public function edit(EditConfigRequest $request){
        $result = $this->configRepository->editConfig($request->only('name', 'group_name', 'description', 'value', 'who_updated','id'));

        return processCommonResponse($result);
    }
}