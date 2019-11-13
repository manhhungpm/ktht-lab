<?php

namespace App\Http\Controllers\Statistic;

use App\Http\Controllers\Controller;
use App\Repositories\Statistic\TypeNumberMsisdnRepository;
use Illuminate\Http\Request;


class TypeNumberMsisdnController extends Controller
{
    protected $_typeNumberMsisdnRepository;

    public function __construct(TypeNumberMsisdnRepository $typeNumberMsisdnRepository)
    {
        $this->middleware('auth');
        $this->_typeNumberMsisdnRepository = $typeNumberMsisdnRepository;
    }

    public function getData(Request $request)
    {
        $arr = [
            'code' => CODE_SUCCESS,
            'data' => $this->_typeNumberMsisdnRepository->getData($request->only('time_filter'))
        ];
        return response()->json($arr);
    }
}