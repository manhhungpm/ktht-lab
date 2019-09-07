<?php

namespace App\Http\Controllers\Brandname;

use App\Http\Controllers\Controller;
use App\Http\Requests\Brandname\Config\AddConfigRequest;
use App\Http\Requests\Brandname\Config\EditConfigRequest;
use App\Http\Requests\Brandname\Config\ActiveConfigRequest;
use App\Http\Requests\Brandname\Config\DisableConfigRequest;
use App\Http\Requests\Common\IdRequest;
use App\Repositories\Brandname\ConfigRepository;
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
            'recordsFiltered' => $total,
        );

        return response()->json($arr);
    }

    public function disable(IdRequest $request)
    {
        $result = $this->configRepository->setDisable($request->input('id'));

        return processCommonResponse($result);
    }

    public function active(ActiveConfigRequest $request)
    {
        $result = $this->configRepository->setActive($request->only('id', 'br_type', 'active'));

        return processCommonResponse($result);
    }

    public function add(AddConfigRequest $request)
    {
        $result = $this->configRepository->addConfig($request->only('date_threshold', 'br_type', 'month_threshold', 'who_created', 'version'));

        return processCommonResponse($result);
    }

    public function edit(EditConfigRequest $request)
    {
        $result = $this->configRepository->editConfig($request->only('id', 'date_threshold', 'month_threshold', 'br_type', 'who_updated'));

        return processCommonResponse($result);
    }

    public function selectVBroadCast(Request $request){
        $result = $this->configRepository->getVBroadCast();

        return ($result);
    }

    public function selectOtherBroadCast(){
        $result = $this->configRepository->getOtherBroadCast();

        return ($result);
    }
}