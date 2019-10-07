<?php

namespace App\Http\Controllers\SpamCallDetail;

use App\Http\Controllers\Controller;
use App\Http\Requests\Phone\PhoneLabelRequest;
use App\Repositories\SpamCallDetail\SpamCallDetailRepository;
use Illuminate\Http\Request;

class SpamCallDetailController extends Controller
{
    protected $_spamCallDetailRepository;

    public function __construct(SpamCallDetailRepository $spamCallDetailRepository)
    {
        $this->middleware('auth');
        $this->_spamCallDetailRepository = $spamCallDetailRepository;
    }

    public function listing(Request $request)
    {
        $params = getDataTableRequestParams($request);
        $searchParams = $request->only('duration_type_id', 'status', 'msisdn', 'carrier');

        $total = $this->_spamCallDetailRepository->getList(
            $params['keyword'],
            $searchParams,
            true
        );

        $arr = array(
            'recordsTotal' => $total,
            'data' => $this->_spamCallDetailRepository->getList(
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

    public function label(PhoneLabelRequest $request)
    {
        $result = $this->_spamCallDetailRepository->setLabel($request->only('phone', 'status'));
        return processCommonResponse($result);
    }

    public function labelMultiple(PhoneLabelRequest $request)
    {
        $result = $this->_spamCallDetailRepository->setLabelMultiple($request->only('phone', 'status'));
        return processCommonResponse($result);
    }
}