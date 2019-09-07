<?php

namespace App\Http\Controllers\Spam;

use App\Http\Controllers\Controller;
use App\Repositories\Spam\SpamPatternsWarningRepository;
use Illuminate\Http\Request;
use App\Exports\Spam\SpamPatterns\WarningExport;

use Maatwebsite\Excel\Excel;

class SpamPatternsWarningController extends Controller
{
    /**
     * @var SpamPatternsRepository
     */
    protected $spamPatternsWarningRepository;

    function __construct(SpamPatternsWarningRepository $spamPatternsWarningRepository)
    {
        $this->middleware('auth');

        $this->spamPatternsWarningRepository = $spamPatternsWarningRepository;
    }


    public function tag(Request $request)
    {

        $result = $this->spamPatternsWarningRepository->tagLabel($request->only('label', 'ids'));

        return processCommonResponse($result);
    }

    public function ignore(Request $request)
    {

        $result = $this->spamPatternsWarningRepository->ignore($request->input('ids'));

        return processCommonResponse($result);
    }

    public function listing(Request $request)
    {
        $params = getDataTableRequestParams($request);

        $searchParams = $request->only('content', 'spam_application', 'when_created');


        $total = $this->spamPatternsWarningRepository->getList(
            $params['keyword'],
            $searchParams,
            true
        );

        $arr = array(
            'recordsTotal' => $total,
            'data' => $this->spamPatternsWarningRepository->getList(
                $params['keyword'],
                $searchParams,
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

    public function listAll()
    {
        $data = $this->spamPatternsWarningRepository->listingSpamPatternsWarning(false);
        $total = $this->spamPatternsWarningRepository->listingSpamPatternsWarning(true);

        return response()->json([
            'results' => $data,
            'total' => $total
        ]);
    }

    public function export(Request $request, Excel $excel)
    {
        $searchParams = $request->only('content', 'spam_application', 'when_created');
        $export = new WarningExport($searchParams);
        return $excel->download($export, 'spam_patterns_warning.xlsx');
    }

    public function listAllSource(Request $request){
        $data = $this->spamPatternsWarningRepository->listAllSource();
        return response()->json([
            'results'=>$data
        ]);
    }

}
