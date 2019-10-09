<?php

namespace App\Http\Controllers\AliasBlockSpam;

use App\Http\Controllers\Controller;
use App\Repositories\AliasBlockSpam\AliasBlockSpamRepository;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;

class AliasBlockSpamController extends Controller
{
    protected $_aliasBlockSpamRepository;

    public function __construct(AliasBlockSpamRepository $aliasBlockSpamRepository)
    {
        $this->middleware('auth');
        $this->_aliasBlockSpamRepository = $aliasBlockSpamRepository;
    }

    public function listing(Request $request)
    {
        $params = getDataTableRequestParams($request);
        $searchParams = $request->only('content_feedback', 'time_feedback', 'survey_phone', 'spam_alias');

        $total = $this->_aliasBlockSpamRepository->getList(
            $params['keyword'],
            $searchParams,
            true
        );

        $arr = array(
            'recordsTotal' => $total,
            'data' => $this->_aliasBlockSpamRepository->getList(
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
}