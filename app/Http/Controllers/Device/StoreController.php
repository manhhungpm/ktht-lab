<?php
/**
 * Created by PhpStorm.
 * User: manhhungpm
 * Date: 04/11/2019
 * Time: 22:10
 */

namespace App\Http\Controllers\Device;

use App\Http\Controllers\Controller;
use App\Http\Requests\Device\Store\AddStoreRequest;
use App\Http\Requests\Device\Store\EditStoreRequest;
use App\Repositories\Device\StoreRepository;
use Illuminate\Http\Request;
use App\Http\Requests\Common\IdRequest;

class StoreController extends Controller
{
    protected $_storeRepository;

    public function __construct(StoreRepository $storeRepository)
    {
        $this->middleware('auth');
        $this->_storeRepository = $storeRepository;
    }

    public function listing(Request $request)
    {
        $params = getDataTableRequestParams($request);

        $total = $this->_storeRepository->getList(
            $params['keyword'],
            true
        );

        $arr = array(
            'recordsTotal' => $total,
            'data' => $this->_storeRepository->getList(
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

    public function add(AddStoreRequest $request)
    {
        $result = $this->_storeRepository->addStore($request->only('name', 'description'),$request->ip());

        return processCommonResponse($result);
    }

    public function edit(EditStoreRequest $request)
    {
        $result = $this->_storeRepository->editStore($request->only('name', 'description', 'id'),$request->ip());

        return processCommonResponse($result);
    }

    public function active(IdRequest $request)
    {
        $result = $this->_storeRepository->setActive($request->only('id'));

        return processCommonResponse($result);
    }

    public function disable(IdRequest $request)
    {
        $result = $this->_storeRepository->setDisable($request->only('id'));

        return processCommonResponse($result);
    }

    public function listingAll(Request $request)
    {
        $length = 10;

        $page = $request->input('page');
        $search = $request->input('search');


        $data = $this->_storeRepository->listingAll(false, $search, $length, $page * $length);
        $total = $this->_storeRepository->listingAll(true, $search);

        return response()->json([
            'results' => $data,
            'total' => $total
        ]);
    }
}
