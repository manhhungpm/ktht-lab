<?php

namespace App\Http\Controllers\Brandname;

use App\Http\Controllers\Controller;
use App\Http\Requests\Common\IdRequest;
use App\Repositories\Brandname\SmsTypeRepository;
use Illuminate\Http\Request;
use App\Http\Requests\Brandname\SmsTypes\AddSmsTypeRequest;
use App\Http\Requests\Brandname\SmsTypes\EditSmsTypeRequest;

class SmsTypeController extends Controller
{
    protected $smsTypeRepository;

    public function __construct(SmsTypeRepository $smsTypeRepository)
    {
        $this->smsTypeRepository = $smsTypeRepository;
    }

    public function listing(Request $request)
    {
        $params = getDataTableRequestParams($request);

        $total = $this->smsTypeRepository->getList(
            $params['keyword'],
            true
        );

        $arr = array(
            'recordsTotal' => $total,
            'data' => $this->smsTypeRepository->getList(
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

    public function add(AddSmsTypeRequest $request)
    {
        $result = $this->smsTypeRepository->addSmsTypes($request->only('name', 'description', 'who_created', 'alias', 'prefix', 'group', 'priority'));

        return processCommonResponse($result);
    }

    public function edit(EditSmsTypeRequest $request)
    {
        $result = $this->smsTypeRepository->editSmsTypes($request->only('name', 'description', 'who_created', 'alias', 'prefix', 'group', 'priority', 'id'));

        return processCommonResponse($result);
    }

    public function delete(IdRequest $request)
    {
        $result = $this->smsTypeRepository->deleteSmsTypes($request->only('id'));

        return processCommonResponse($result);
    }

    public function active(IdRequest $request)
    {
        $result = $this->smsTypeRepository->active($request->only( 'id'));

        return processCommonResponse($result);
    }

    public function disable(IdRequest $request)
    {
        $result = $this->smsTypeRepository->disable($request->only( 'id'));

        return processCommonResponse($result);
    }

    public function listingAll(Request $request)
    {
        $length = 10;

        $page = $request->input('page');
        $search = $request->input('search');
        $smsGroupId = null;

        if ($request->has('sms_group_id'))
            $smsGroupId = $request->input('sms_group_id');

        $data = $this->smsTypeRepository->listingSmsGroup(false, $search, $smsGroupId, $length, $page * $length);
        $total = $this->smsTypeRepository->listingSmsGroup(true, $search, $smsGroupId);

        return response()->json([
            'results' => $data,
            'total' => $total
        ]);
    }
}
