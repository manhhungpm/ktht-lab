<?php

namespace App\Http\Controllers\Statistic;

use App\Http\Controllers\Controller;
use App\Repositories\Statistic\TypeNumberOutCallRepository;
use Illuminate\Http\Request;

class TypeNumberOutCallController extends Controller
{
    protected $_typeNumberOutCallRepository;

    public function __construct(TypeNumberOutCallRepository $typeNumberOutCallRepository)
    {
        $this->middleware('auth');
        $this->_typeNumberOutCallRepository = $typeNumberOutCallRepository;
    }

    public function getData(Request $request)
    {
        $arr = [
            'code' => CODE_SUCCESS,
            'data' => $this->_typeNumberOutCallRepository->getData($request->only('time_filter'))
        ];
        return response()->json($arr);
    }
}