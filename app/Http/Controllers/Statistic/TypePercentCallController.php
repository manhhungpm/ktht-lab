<?php

namespace App\Http\Controllers\Statistic;

use App\Http\Controllers\Controller;
use App\Repositories\Statistic\TypePercentCallRepository;
use Illuminate\Http\Request;

class TypePercentCallController extends Controller
{
    protected $_typePercentCallRepository;

    public function __construct(TypePercentCallRepository $typePercentCallRepository)
    {
        $this->middleware('auth');
        $this->_typePercentCallRepository = $typePercentCallRepository;
    }

    public function getData(Request $request)
    {
        $arr = [
            'code' => CODE_SUCCESS,
            'data' => $this->_typePercentCallRepository->getData($request->only('time_filter'))
        ];
        return response()->json($arr);
    }
}