<?php

namespace App\Http\Controllers\Device;

use App\Http\Controllers\Controller;
use App\Repositories\Device\ProviderRepository;
use Illuminate\Http\Request;
use App\Http\Requests\Common\IdRequest;

class ProviderController extends Controller
{
    protected $_providerRepository;

    public function __construct(ProviderRepository $providerRepository)
    {
        $this->middleware('auth');
        $this->_providerRepository = $providerRepository;
    }

    public function listing(Request $request)
    {
        $params = getDataTableRequestParams($request);

        $total = $this->_providerRepository->getList(
            $params['keyword'],
            true
        );

        $arr = array(
            'recordsTotal' => $total,
            'data' => $this->_providerRepository->getList(
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

    public function add(Request $request)
    {
        $result = $this->_providerRepository->addProvider($request->only('name','address', 'description'),$request->ip());

        return processCommonResponse($result);
    }

    public function edit(Request $request)
    {
        $result = $this->_providerRepository->editProvider($request->only('name','address', 'description', 'id'),$request->ip());

        return processCommonResponse($result);
    }

    public function active(IdRequest $request)
    {
        $result = $this->_providerRepository->setActive($request->only('id'));

        return processCommonResponse($result);
    }

    public function disable(IdRequest $request)
    {
        $result = $this->_providerRepository->setDisable($request->only('id'));

        return processCommonResponse($result);
    }

    public function listingAll(Request $request)
    {
        $length = 10;

        $page = $request->input('page');
        $search = $request->input('search');


        $data = $this->_providerRepository->listingAll(false, $search, $length, $page * $length);
        $total = $this->_providerRepository->listingAll(true, $search);

        return response()->json([
            'results' => $data,
            'total' => $total
        ]);
    }
}