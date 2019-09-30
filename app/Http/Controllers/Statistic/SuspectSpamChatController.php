<?php

namespace App\Http\Controllers\Statistic;

use App\Http\Controllers\Controller;
use App\Repositories\Spam\SpamPatternRepository;
use App\Repositories\Spam\SpamPatternsWarningRepository;
use App\Repositories\Spam\SpamPhoneRepository;
use App\Repositories\Statistic\SuspectSpamChatRepository;
use Illuminate\Http\Request;
use App\Exports\Spam\SpamPatterns\WarningExport;

use Maatwebsite\Excel\Excel;

class SuspectSpamChatController extends Controller
{
    /**
     * @var SuspectSpamChatRepository
     */
    protected $suspectSpamChatRepository;

    /**
     * SuspectSpamChatController constructor.
     * @param SuspectSpamChatRepository $suspectSpamChatRepository
     */

    function __construct(SuspectSpamChatRepository $suspectSpamChatRepository)
    {
        $this->middleware('auth');

        $this->suspectSpamChatRepository = $suspectSpamChatRepository;
    }

    function listing(Request $request){
        $arr = [
            'code' => CODE_SUCCESS,
            'data' => $this->suspectSpamChatRepository->listing($request->only('from', 'to'))
        ];
        return response()->json($arr);
    }
}
