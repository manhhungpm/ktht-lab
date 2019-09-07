<?php
/**
 * Created by PhpStorm.
 * User: hungnm
 * Date: 7/12/2019
 * Time: 8:26 AM
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\SmsTopicRepository;
use Illuminate\Http\Request;

class SmsTopicController extends Controller
{
    protected $smsTopicRepository;

    public function __construct(SmsTopicRepository $smsTopicRepository)
    {
        $this->smsTopicRepository = $smsTopicRepository;
    }

    public function listing(Request $request)
    {
        $params = getDataTableRequestParams($request);

        $total = $this->smsTopicRepository->getList(
            $params['keyword'],
            true
        );

        $arr = array(
            'recordsTotal' => $total,
            'data' => $this->smsTopicRepository->getList(
                $params['keyword'],
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
}