<?php


namespace App\Http\Controllers\Statistic;

use App\Http\Controllers\Controller;
use App\Repositories\Statistic\MsisdnSummaryTypeRepository;
use App\Repositories\Statistic\TypeDurationMsisdnRepository;
use Illuminate\Http\Request;

class TypeDurationMsisdnController extends Controller
{
    protected $_typeDurationMsisdnRepository;

    public function __construct(TypeDurationMsisdnRepository $typeDurationMsisdnRepository)
    {
        $this->middleware('auth');
        $this->_typeDurationMsisdnRepository = $typeDurationMsisdnRepository;
    }

    function getData(Request $request){

        $arr = [
            'code' => CODE_SUCCESS,
            'data' => $this->_typeDurationMsisdnRepository->getData($request->only('from', 'to'))
        ];
        return response()->json($arr);
    }
}