<?php

namespace App\Http\Controllers\Spam;

use App\Exports\Spam\SpamPatterns\LabeledExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Spam\SpamPatterns\Labeled\AddLabelRequest;
use App\Http\Requests\Spam\SpamPatterns\Labeled\IgnoreLabelsRequest;
use App\Http\Requests\Spam\SpamPatterns\Labeled\TagLabelsRequest;
use App\Repositories\Spam\SpamPatternsLabeledRepository;
use Illuminate\Http\Request;

use Maatwebsite\Excel\Excel;

class SpamPatternsLabeledController extends Controller
{
    /**
     * @var SpamPatternsRepository
     */
    protected $spamPatternsLabeledRepository;

    function __construct(SpamPatternsLabeledRepository $spamPatternsLabeledRepository)
    {
        $this->middleware('auth');

        $this->spamPatternsLabeledRepository = $spamPatternsLabeledRepository;
    }


    public function tag(TagLabelsRequest $request)
    {

        $result = $this->spamPatternsLabeledRepository->tagLabel($request->only('label', 'ids'));

        return processCommonResponse($result);
    }

    public function add(AddLabelRequest $request)
    {

        $result = $this->spamPatternsLabeledRepository->addLabel($request->only('label', 'content'));

        return processCommonResponse($result);
    }

    public function ignore(IgnoreLabelsRequest $request)
    {

        $result = $this->spamPatternsLabeledRepository->ignore($request->input('ids'));

        return processCommonResponse($result);
    }

    public function listing(Request $request)
    {
        $params = getDataTableRequestParams($request);

        $searchParams = $request->only('content', 'spam_application', 'when_updated', 'username', 'label');


        $total = $this->spamPatternsLabeledRepository->getList(
            $params['keyword'],
            $searchParams,
            true
        );

        $arr = array(
            'recordsTotal' => $total,
            'data' => $this->spamPatternsLabeledRepository->getList(
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
        $data = $this->spamPatternsLabeledRepository->listingSpamPatternsWarning(false);
        $total = $this->spamPatternsLabeledRepository->listingSpamPatternsWarning(true);

        return response()->json([
            'results' => $data,
            'total' => $total
        ]);
    }

    public function export(Request $request, Excel $excel)
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '0');
        $searchParams = $request->only('content', 'spam_application', 'when_updated', 'username', 'label');
        $locale = $request->cookie('locale');
        \Illuminate\Support\Facades\App::setLocale($locale);
        $export = new LabeledExport($searchParams);
        return $excel->download($export, 'spam_patterns_labeled.xlsx');
    }

    public function listAllSource(Request $request)
    {
        $data = $this->spamPatternsLabeledRepository->listAllSource();
        return response()->json([
            'results' => $data
        ]);
    }

}
