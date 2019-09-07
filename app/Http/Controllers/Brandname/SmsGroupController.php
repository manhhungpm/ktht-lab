<?php

namespace App\Http\Controllers\Brandname;

use App\Http\Controllers\Controller;
use App\Http\Requests\Common\IdRequest;
use App\Repositories\Brandname\SmsGroupRepository;
use App\Http\Requests\Brandname\SmsGroups\AddSmsGroupRequest;
use App\Http\Requests\Brandname\SmsGroups\EditSmsGroupRequest;
use Illuminate\Http\Request;

class SmsGroupController extends Controller
{
    protected $smsGroupRepository;

    public function __construct(SmsGroupRepository $smsGroupRepository)
    {
        $this->smsGroupRepository = $smsGroupRepository;
    }

    public function listing(Request $request)
    {
        $params = getDataTableRequestParams($request);

        $total = $this->smsGroupRepository->getList(
            $params['keyword'],
            true
        );

        $arr = array(
            'recordsTotal' => $total,
            'data' => $this->smsGroupRepository->getList(
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

    public function add(AddSmsGroupRequest $request)
    {
        $result = $this->smsGroupRepository->addSmsGroups($request->only('name', 'description', 'who_created'));

        return processCommonResponse($result);
    }

    public function edit(EditSmsGroupRequest $request)
    {
        $result = $this->smsGroupRepository->editSmsGroups($request->only('name', 'description', 'who_updated', 'id'));

        return processCommonResponse($result);
    }

    public function delete(IdRequest $request)
    {
        $result = $this->smsGroupRepository->deleteSmsGroups($request->only( 'id'));

        return processCommonResponse($result);
    }

    public function active(IdRequest $request)
    {
        $result = $this->smsGroupRepository->active($request->only( 'id'));

        return processCommonResponse($result);
    }

    public function disable(IdRequest $request)
    {
        $result = $this->smsGroupRepository->disable($request->only( 'id'));

        return processCommonResponse($result);
    }

    public function listingAll(Request $request)
    {
        $length = 10;

        $page = $request->input('page');
        $search = $request->input('search');


        $data = $this->smsGroupRepository->listingAll(false, $search, $length, $page * $length);
        $total = $this->smsGroupRepository->listingAll(true, $search);

        return response()->json([
            'results' => $data,
            'total' => $total
        ]);
    }
}