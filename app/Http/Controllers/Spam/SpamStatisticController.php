<?php

namespace App\Http\Controllers\Spam;

use App\Http\Controllers\Controller;
use App\Repositories\Spam\SpamPatternRepository;
use App\Repositories\Spam\SpamPatternsWarningRepository;
use App\Repositories\Spam\SpamPhoneRepository;
use Illuminate\Http\Request;
use App\Exports\Spam\SpamPatterns\WarningExport;

use Maatwebsite\Excel\Excel;

class SpamStatisticController extends Controller
{
    /**
     * @var SpamPatternRepository
     */
    protected $spamPatternRepository;

    /**
     * @var SpamPhoneRepository
     */
    protected $spamPhoneRepository;

    function __construct(SpamPatternRepository $spamPatternRepository, SpamPhoneRepository $spamPhoneRepository)
    {
        $this->middleware('auth');

        $this->spamPatternRepository = $spamPatternRepository;
        $this->spamPhoneRepository = $spamPhoneRepository;
    }

    function getPatternStatistic(Request $request)
    {
        $from = $request->get('from');
        $to = $request->get('to');
        $arr = [
            'total' => $this->spamPatternRepository->spamPatternCountBetween($from, $to),
            'spam' => $this->spamPatternRepository->spamPatternCountBetween($from, $to, 'spam'),
            'ham' => $this->spamPatternRepository->spamPatternCountBetween($from, $to, 'ham'),
            'ignore' => $this->spamPatternRepository->spamPatternCountBetween($from, $to, 'ignore'),
            'customer_feedback' => $this->spamPatternRepository->spamPatternCountBetween($from, $to, null, 'customer_feedback'),
            'customer_feedback_spam' => $this->spamPatternRepository->spamPatternCountBetween($from, $to, 'spam', 'customer_feedback')
        ];
        return response()->json($arr);
    }

    function getPhoneStatistic(Request $request)
    {
        $from = $request->get('from');
        $to = $request->get('to');
        $arr = [
            'internal_banned' => $this->spamPhoneRepository->countInternalPhones($from, $to),
            'external_banned' => $this->spamPhoneRepository->countExternalPhones($from, $to),
            'ignore' => $this->spamPhoneRepository->countIgnorePhones($from, $to)
        ];
        return response()->json($arr);
    }
}
