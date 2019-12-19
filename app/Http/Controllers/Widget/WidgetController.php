<?php

namespace App\Http\Controllers\Widget;

use App\Http\Controllers\Controller;
use App\Repositories\Widget\WidgetRepository;
use Illuminate\Http\Request;


class WidgetController extends Controller
{
    protected $_widgetRepository;

    public function __construct(WidgetRepository $widgetRepository)
    {
        $this->middleware('auth');
        $this->_widgetRepository = $widgetRepository;
    }

    public function getData(Request $request)
    {
        $arr = [
            'code' => CODE_SUCCESS,
            'data' => $this->_widgetRepository->getData()
        ];
        return response()->json($arr);
    }
}