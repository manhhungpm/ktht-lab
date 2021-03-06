<?php

namespace App\Http\Controllers;

use App\Repositories\A2pKeywordRepository;
use App\Repositories\A2pPatternRepository;

use Illuminate\Http\Request;

class DashboardController extends Controller
{

    protected $spamPatternRepository;
    protected $spamPhoneRepository;
    protected $spamReviewMissingRepository;
    protected $spamReviewWrongRepository;
    protected $a2pPatternRepository;
    protected $a2pKeywordRepository;
    protected $spamBlockStatRepository;

    function __construct(
        A2pPatternRepository $a2pPatternRepository,
        A2pKeywordRepository $a2pKeywordRepository

    )
    {
        $this->middleware('auth');

    }

    function spamWarning()
    {
        $response = [
            'code' => CODE_SUCCESS,
//            'results' => [
//                'unlabel_count' => $this->spamPatternRepository->dashboardCount(),
//                'warning_phone_count' => $this->spamPhoneRepository->dashboardCount(),
//                'review_missing_count' => $this->spamReviewMissingRepository->dashboardCount(),
//                'review_wrong_count' => $this->spamReviewWrongRepository->dashboardCount(),
//            ]
        ];
        return response()->json($response);
    }

    function a2pWarning()
    {
        $response = [
            'code' => CODE_SUCCESS,
//            'results' => [
//                'remain_pattern_count' => $this->a2pPatternRepository->dashboardRemainA2PCount(),
//                'pattern_count' => $this->a2pPatternRepository->dashboardA2PCount(),
//                'keyword_count' => $this->a2pKeywordRepository->dashboardCount(),
//            ]
        ];
        return response()->json($response);
    }

//    public
//    function listing(Request $request)
//    {
//        $params = getDataTableRequestParams($request);
//
////        $searchParams = $request->only('partner_id', 'service_id', 'effective_time_from', 'effective_time_to');
//        $searchParams = [];
//
//
//        $total = $this->spamBlockStatRepository->getList(
//            $params['keyword'],
//            $searchParams,
//            true
//        );
//
//        $arr = array(
//            'recordsTotal' => $total,
//            'data' => $this->spamBlockStatRepository->getList(
//                $params['keyword'],
//                $searchParams,
//                false,
//                $params['length'],
//                $params['start'],
//                $params['orderBy'],
//                $params['orderType']
//            ),
//            'draw' => $params['draw'],
//            'recordsFiltered' => $total
//        );
//
//        return response()->json($arr);
//    }
}