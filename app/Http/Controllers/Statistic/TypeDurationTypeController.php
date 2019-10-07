<?php

namespace App\Http\Controllers\Statistic;

use App\Http\Controllers\Controller;
use App\Repositories\Statistic\TypeDurationTypeRepository;
use Illuminate\Http\Request;

class TypeDurationTypeController extends Controller
{
    protected $_typeDurationTypeRepository;

    public function __construct(TypeDurationTypeRepository $typeDurationTypeRepository)
    {
        $this->middleware('auth');
        $this->_typeDurationTypeRepository = $typeDurationTypeRepository;
    }

    function getData(Request $request){

        $arr = [
            'code' => CODE_SUCCESS,
            'data' => $this->_typeDurationTypeRepository->getData($request->only('time_filter'))
        ];
        return response()->json($arr);
    }
}